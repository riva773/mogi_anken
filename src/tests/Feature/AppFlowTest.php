<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;




class AppFlowTest extends TestCase
{
    use RefreshDatabase;

    // ===== Helpers =====
    private function createUser(array $overrides = []): User
    {
        return User::query()->create([
            'name'     => $overrides['name']     ?? '太郎',
            'email'    => $overrides['email']    ?? Str::uuid() . '@example.com',
            'password' => bcrypt($overrides['password'] ?? 'password123'),
            'postal_code' => $overrides['postal_code'] ?? null,
            'address'     => $overrides['address'] ?? null,
            'building'    => $overrides['building'] ?? null,
        ]);
    }

    protected function createItem(array $overrides = [])
    {
        return Item::factory()->create($overrides);
    }

    // ======================== 1. 会員登録 ========================
    public function test_registration_requires_name()
    {
        $res = $this->from('/register')->post('/register', [
            'name' => '',
            'email' => 'a@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $res->assertRedirect('/register');
        $res->assertSessionHasErrors(['name' => 'お名前を入力してください']);
    }

    public function test_registration_requires_email()
    {
        $res = $this->from('/register')->post('/register', [
            'name' => '太郎',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $res->assertRedirect('/register');
        $res->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    public function test_registration_requires_password()
    {
        $res = $this->from('/register')->post('/register', [
            'name' => '太郎',
            'email' => 'a@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);
        $res->assertRedirect('/register');
        $res->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    public function test_registration_password_min_8()
    {
        $res = $this->from('/register')->post('/register', [
            'name' => '太郎',
            'email' => 'a@example.com',
            'password' => '1234567',
            'password_confirmation' => '1234567',
        ]);
        $res->assertRedirect('/register');
        $res->assertSessionHasErrors(['password' => 'パスワードは8文字以上で入力してください']);
    }

    public function test_registration_redirects_to_email_verify_notice()
    {
        Notification::fake();

        $res = $this->post('/register', [
            'name' => '太郎',
            'email' => 'a@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // 登録直後はメール認証案内へ
        $res->assertRedirect('/email/verify');

        // ユーザーは作成されている
        $user = \App\Models\User::where('email', 'a@example.com')->firstOrFail();
        $this->assertNotNull($user);

        // メール認証通知が送られている
        Notification::assertSentTo($user, VerifyEmail::class);

        $this->assertAuthenticatedAs($user, 'web');
    }

    public function test_full_onboarding_flow_verifies_email_then_profile_setup_then_logged_in()
    {
        Notification::fake();

        // 1) 登録
        $this->post('/register', [
            'name' => '太郎',
            'email' => 'b@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect('/email/verify');

        $user = \App\Models\User::where('email', 'b@example.com')->firstOrFail();

        // 2) メール認証リンクを再現
        $verifyUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        // 認証リンクアクセス
        $this->actingAs($user)->get($verifyUrl)
            ->assertRedirect('/mypage/profile');

        // メールが検証済みになっている
        $this->assertTrue($user->fresh()->hasVerifiedEmail());

        // 3) 初回プロフィール設定を送信
        $this->put('/mypage/profile', [
            'name' => '太郎',
            'postal_code' => '1234567',
            'address' => '東京都千代田区1-1',
            'building' => '丸の内ビル101',
        ])
            ->assertRedirect('/');

        // 4) 以後はログイン状態であること
        $this->assertAuthenticatedAs($user, 'web');
    }





    // ========================== 2. ログイン ==========================
    public function test_login_requires_email()
    {
        $res = $this->from('/login')->post('/login', [
            'email' => '',
            'password' => 'password123',
        ]);
        $res->assertRedirect('/login');
        $res->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    public function test_login_requires_password()
    {
        $res = $this->from('/login')->post('/login', [
            'email' => 'a@example.com',
            'password' => '',
        ]);
        $res->assertRedirect('/login');
        $res->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    public function test_login_with_wrong_credentials_shows_error()
    {
        $res = $this->from('/login')->post('/login', [
            'email' => 'not-exist@example.com',
            'password' => 'wrongpass',
        ]);
        $res->assertRedirect('/login');
        $res->assertSessionHasErrors(['email' => 'ログイン情報が登録されていません。']);
        $this->assertGuest();
    }

    public function test_login_success_authenticates_user()
    {
        // 1) 事前に“メール認証済み”ユーザーを用意
        $user = $this->createUser([
            'email' => 'a@example.com',
            'password' => 'password123',
        ]);
        // メール認証フラグを立てる
        $user->forceFill(['email_verified_at' => now()])->save();

        // 2) ログイン実行
        $res = $this->from('/login')->post('/login', [
            'email' => 'a@example.com',
            'password' => 'password123',
        ]);

        // 3) 期待：ログイン成功し、想定ページへ遷移
        $res->assertRedirect('/');
        $this->assertAuthenticatedAs($user, 'web');
    }

    // ========================== 3. ログアウト ==========================
    public function test_can_logout()
    {
        $user = $this->createUser();
        $this->actingAs($user);
        $res = $this->post('/logout');
        $res->assertRedirect('/');
        $this->assertGuest();
    }

    // ======================== 4. 商品一覧 =========================
    public function test_items_index_shows_all_items()
    {
        Item::factory()->create(['name' => 'A']);
        Item::factory()->create(['name' => 'B']);
        $res = $this->get('/');
        $res->assertOk()->assertSee('A')->assertSee('B');
    }

    public function test_items_index_shows_Sold_label_for_purchased_items()
    {
        Item::factory()->sold()->create(['name' => '売れた']);
        $res = $this->get('/');
        $res->assertOk();
        $res->assertSee('sold');
        $res->assertSee('売れた');
    }

    public function test_items_index_hides_my_own_items_when_logged_in()
    {
        $me = User::factory()->create();
        $this->actingAs($me);
        Item::factory()->seller($me)->create(['name' => '自分の出品']);
        Item::factory()->create(['name' => '他人の出品']);
        $res = $this->get('/');
        $res->assertOk();
        $res->assertDontSee('自分の出品');
        $res->assertSee('他人の出品');
    }

    // ===================== 5. マイリスト ====================
    public function test_mylist_shows_only_liked_items_and_hides_mine_and_marks_sold()
    {
        $me = $this->createUser();
        $this->actingAs($me);

        $liked = $this->createItem(['name' => 'いいね済み', 'condition' => '良好']);
        $mine  = $this->createItem(['name' => '自分の出品', 'seller_id' => $me->id, 'condition' => '良好']);
        $sold  = Item::factory()
            ->sold()
            ->create(['name' => '売れた商品', 'condition' => '良好']);


        \DB::table('likes')->insert([
            'user_id' => $me->id,
            'item_id' => $liked->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        \DB::table('likes')->insert([
            'user_id' => $me->id,
            'item_id' => $sold->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $res = $this->get('/?page=mylist');
        $res->assertOk();
        $res->assertSee('いいね済み')->assertSee('売れた商品');
        $res->assertSee('Sold');
        $res->assertDontSee('自分の出品');
    }

    public function test_mylist_shows_empty_for_guest()
    {
        $res = $this->get('/?page=mylist');
        $res->assertOk();
        $res->assertSee('表示できる商品がありません。');
    }

    // ======================== 6. 検索 ========================
    public function test_search_by_name_returns_partial_matches_and_keyword_persists_to_mylist()
    {
        $this->createItem(['name' => 'りんごジュース']);
        $this->createItem(['name' => 'ぶどうジュース']);
        $this->createItem(['name' => '炭酸水']);

        $res = $this->get('/?q=ジュース');
        $res->assertOk();
        $res->assertSee('りんごジュース')->assertSee('ぶどうジュース')->assertDontSee('炭酸水');

        $res2 = $this->get('/?page=mylist&q=ジュース');
        $res2->assertOk();
        $res2->assertSee('value="ジュース"', false);
    }

    // ===================== 7. 商品詳細（複数カテゴリ） =====================
    public function test_item_show_displays_all_required_information_including_multi_categories()
    {
        $item = $this->createItem([
            'name' => 'テスト用アクセサリー',
            'brand' => 'テストブランド',
            'price' => 3000,
            'categories' => [
                'ファッション',
                'アクセサリー'
            ],
        ]);

        // コメントを複数作成
        $commentsCount = 3;
        $commentUsers  = [];
        for ($i = 0; $i < $commentsCount; $i++) {
            $u = $this->createUser();
            $commentUsers[] = $u;
            Comment::query()->create([
                'user_id' => $u->id,
                'item_id' => $item->id,
                'content' => $i === 0 ? '綺麗' : "コメント{$i}",
            ]);
        }

        // いいね（likes）を複数作成
        $likesCount = 4;
        for ($i = 0; $i < $likesCount; $i++) {
            $u = $this->createUser();
            \DB::table('likes')->insert([
                'user_id'    => $u->id,
                'item_id'    => $item->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        // ページへアクセス
        $res = $this->get("/item/{$item->id}");
        $res->assertOk();

        // 基本情報
        $res->assertSeeText('テスト用アクセサリー')
            ->assertSeeText('テストブランド')
            ->assertSeeText('3,000')
            ->assertSeeText('良好');

        // カテゴリ（複数）
        $res->assertSeeText('ファッション')
            ->assertSeeText('アクセサリー');

        // コメント（内容と件数の存在を確認）
        $res->assertSeeText('綺麗');
        $res->assertSeeText((string)$commentsCount);

        // いいね数の存在を確認
        $res->assertSeeText((string)$likesCount);
    }

    // ========================== 8. いいね ==========================
    public function test_like_and_unlike_item_changes_counter_and_icon_state()
    {
        $user = $this->createUser();
        $this->actingAs($user);
        $item = $this->createItem(['condition' => '良好']);
        $page0 = $this->get("/item/{$item->id}");
        $page0->assertOk();
        $page0->assertSee('data-testid="likes-count">0<', false);
        $page0->assertSee('data-testid="comments-count">0<', false);
        $page0->assertSee('data-liked="false"', false);
        $page0->assertSee('aria-pressed="false"', false);

        // いいね
        $this->post("/item/{$item->id}/like")->assertStatus(302);
        $this->assertDatabaseHas('likes', ['user_id' => $user->id, 'item_id' => $item->id]);

        $page1 = $this->get("/item/{$item->id}");
        $page1->assertOk();
        $page1->assertSee('data-testid="likes-count">1<', false);
        $page1->assertSee('data-liked="true"', false);
        $page1->assertSee('aria-pressed="true"', false);

        // いいね解除
        $this->delete("/item/{$item->id}/like")->assertStatus(302);
        $this->assertDatabaseMissing('likes', ['user_id' => $user->id, 'item_id' => $item->id]);

        $page2 = $this->get("/item/{$item->id}");
        $page2->assertOk();
        $page2->assertSee('data-testid="likes-count">0<', false);
        $page2->assertSee('data-liked="false"', false);
        $page2->assertSee('aria-pressed="false"', false);
    }


    // ========================== 9. コメント ==========================
    public function test_guest_cannot_post_comment_and_logged_in_can_with_validation()
    {
        $item = $this->createItem();

        // ゲスト：投稿できず /login へ
        $guest = $this->post(route('item.comments.store', $item), ['content' => 'NG']);
        $guest->assertStatus(302);
        $guest->assertRedirect('/login');
        $this->assertDatabaseMissing('comments', ['item_id' => $item->id, 'content' => 'NG']);

        // ログイン
        $user = $this->createUser();
        $this->actingAs($user);

        // 空入力 → バリデーション
        $empty = $this->from(route('items.show', ['item_id' => $item->id]))
            ->post(route('item.comments.store', $item), ['content' => '']);
        $empty->assertRedirect(route('items.show', ['item_id' => $item->id]));
        $empty->assertSessionHasErrors(['content' => '商品コメントは必須です。']);

        // 256文字 → バリデーション
        $long = str_repeat('あ', 256);
        $tooLong = $this->from(route('items.show', ['item_id' => $item->id]))
            ->post(route('item.comments.store', $item), ['content' => $long]);
        $tooLong->assertRedirect(route('items.show', ['item_id' => $item->id]));
        $tooLong->assertSessionHasErrors(['content' => '商品コメントは255文字以内で入力してください。']);

        // 正常
        $ok = $this->post(route('item.comments.store', $item), ['content' => '買います！']);
        $ok->assertStatus(302);
        $this->assertDatabaseHas('comments', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'content' => '買います！',
        ]);
    }
    // ========================== 10. 購入 ==========================
    public function test_purchase_flow_marks_item_sold_and_lists_in_profile()
    {
        $buyer = $this->createUser();
        $this->actingAs($buyer);
        $item = $this->createItem(['name' => '買われる商品', 'condition' => '良好']);

        $res = $this->post(
            route('orders.store', ['item_id' => $item->id]),
            [
                'payment_method' => 'コンビニ支払い',
                'shipping' => [
                    'name'        => 'テスト太郎',
                    'postal_code' => '1234567',
                    'address'     => '東京都千代田区1-1-1',
                    'building'    => '',
                ],
            ]
        );

        $res->assertRedirect(route('items.index'));

        $this->assertDatabaseHas('items', [
            'id'       => $item->id,
            'status'   => 'sold',
            'buyer_id' => $buyer->id,
        ]);

        $list = $this->get(route('items.index'));
        $list->assertOk()->assertSee('Sold');

        $profile = $this->get(route('mypage', ['page' => 'buy']));
        $profile->assertOk()->assertSee('買われる商品');
    }

    // ======= 11. 支払い方法選択 =======
    public function test_payment_method_selection_reflects_in_summary()
    {
        $user = $this->createUser();
        $this->actingAs($user);
        $item = $this->createItem(['condition' => '良好']);

        // 初期表示は「未選択」
        $page0 = $this->get(route('orders.create', ['item_id' => $item->id]));
        $page0->assertOk()
            ->assertSee('id="select_method">未選択<', false);

        // ユーザーがプルダウンで「カード支払い」を選んだ後の状態を再現
        $page1 = $this
            ->withSession(['_old_input' => ['payment_method' => 'カード支払い']])
            ->get(route('orders.create', ['item_id' => $item->id]));

        $page1->assertOk();
        // 表示が更新されていること
        $page1->assertSee('id="select_method">カード支払い<', false);
        // セレクトの選択状態も確認
        $page1->assertSee('<option value="カード支払い" selected', false);

        $page2 = $this
            ->withSession(['_old_input' => ['payment_method' => 'コンビニ支払い']])
            ->get(route('orders.create', ['item_id' => $item->id]));

        $page2->assertOk()
            ->assertSee('id="select_method">コンビニ支払い<', false)
            ->assertSee('<option value="コンビニ支払い" selected', false);
    }


    // ======= 12. 配送先住所変更 =======
    public function test_address_change_reflects_on_purchase_and_is_attached_to_order()
    {
        $user = $this->createUser();
        $this->actingAs($user);

        // 購入対象の商品
        $item = $this->createItem(['condition' => '良好']);

        // 1) 送付先住所を更新
        $addr = [
            'postal_code' => '1234567',
            'address'     => '東京都千代田区1-1',
            'building'    => '丸の内ビル101',
        ];
        $this->post(route('purchase.address.update', ['item_id' => $item->id]), $addr)
            ->assertStatus(302);

        // 2) 購入画面に反映されている
        $purchase = $this->get(route('orders.create', ['item_id' => $item->id]));
        $purchase->assertOk()
            ->assertSee('123-4567')
            ->assertSee('東京都千代田区1-1')
            ->assertSee('丸の内ビル101');

        // 3) その住所で購入
        $this->post(route('orders.store', ['item_id' => $item->id]), [
            'payment_method' => 'コンビニ支払い',
            'shipping' => [
                'name'        => 'test商品',
                'postal_code' => $addr['postal_code'],
                'address'     => $addr['address'],
                'building'    => $addr['building'],
            ],
        ])
            ->assertStatus(302);

        // 4) 住所はアイテム×ユーザーに紐付いて保存されている
        $this->assertDatabaseHas('item_shipping_overrides', [
            'item_id'     => $item->id,
            'user_id'     => $user->id,
            'postal_code' => $addr['postal_code'],
            'address'     => $addr['address'],
            'building'    => $addr['building'],
        ]);

        // 5) アイテムは購入済みに
        $this->assertDatabaseHas('items', [
            'id'       => $item->id,
            'status'   => 'sold',
            'buyer_id' => $user->id,
        ]);
    }

    // ======= 13. ユーザー情報取得 =======
    public function test_profile_displays_avatar_name_selling_and_purchased_lists()
    {
        $user = $this->createUser(['name' => '表示太郎', 'avatar_url' => '/images/avatar.png']);
        $this->actingAs($user);

        // 出品した商品（自分が seller）
        $sell = $this->createItem([
            'name'       => '出品A',
            'seller_id'  => $user->id,
        ]);

        // 購入した商品（自分が buyer）
        $buy  = $this->createItem([
            'name'       => '購入B',
            'status'     => 'sold',
            'buyer_id'   => $user->id,
        ]);

        // タブ：出品した商品
        $resSell = $this->get(route('mypage', ['page' => 'sell']));
        $resSell->assertOk();
        $resSell->assertSeeText('表示太郎');
        // アバター(img要素)の存在を alt で確認
        $resSell->assertSee('alt="プロフィール画像"', false);
        $resSell->assertSeeText('出品A');
        $resSell->assertDontSeeText('購入B'); // sellタブでは購入品は出ない

        // タブ：購入した商品
        $resBuy = $this->get(route('mypage', ['page' => 'buy']));
        $resBuy->assertOk();
        $resBuy->assertSeeText('表示太郎');
        $resBuy->assertSee('alt="プロフィール画像"', false);
        $resBuy->assertSeeText('購入B');
        $resBuy->assertDontSeeText('出品A'); // buyタブでは出品品は出ない
    }


    // ======= 14. プロフィール編集：初期値の表示（堅牢版） =======
    public function test_profile_edit_form_is_prefilled_with_existing_values()
    {
        // 事前データ
        $user = $this->createUser([
            'name'       => '初期太郎',
            'avatar_url' => '/images/avatar.png',
        ]);
        $this->actingAs($user);

        $user->update([
            'postal_code' => '9876543',
            'address'     => '大阪府大阪市2-2',
            'building'    => '梅田ビル202',
        ]);

        // 編集フォームへ
        $res = $this->get(route('mypage.profile'));
        $res->assertOk();

        $html = $res->getContent();

        // 画像
        $res->assertSee('alt="avatar"', false);

        // ユーザー名：value に '初期太郎' が入っていること
        $this->assertMatchesRegularExpression(
            '/<input[^>]*name="name"[^>]*value="初期太郎"/u',
            $html
        );

        // 郵便番号
        $this->assertMatchesRegularExpression(
            '/<input[^>]*name="postal_code"[^>]*id="postal_code"[^>]*value="987-?6543"/u',
            $html,
            'postal_code input should be prefilled (with or without hyphen).'
        );

        // 住所
        $this->assertMatchesRegularExpression(
            '/<input[^>]*name="address"[^>]*value="大阪府大阪市2-2"/u',
            $html
        );

        // 建物名
        $this->assertMatchesRegularExpression(
            '/<input[^>]*name="building"[^>]*value="梅田ビル202"/u',
            $html
        );
    }
    // ======= 15. 出品登録（複数カテゴリ＋状態） =======
    public function test_can_store_item_with_required_fields_including_multi_categories_and_condition()
    {
        $user = $this->createUser();
        $this->actingAs($user);

        \Illuminate\Support\Facades\Storage::fake('public');

        $cats = ['ファッション', 'アクセサリー'];

        $payload = [
            'name'        => '出品テスト',
            'description' => '説明テキスト',
            'price'       => 1980,
            'condition'   => '良好',
            'categories'  => $cats,
            'image'       => \Illuminate\Http\UploadedFile::fake()->create('item.jpeg', 10, 'image/jpeg'),
        ];

        $res = $this->post(route('items.store'), $payload);
        $res->assertStatus(302);

        $res->assertSessionHasNoErrors();

        $item = \App\Models\Item::query()
            ->where('seller_id', $user->id)
            ->latest('id')
            ->first();

        $this->assertNotNull($item, 'item should be created');
        $res->assertRedirect(route('items.show', ['item_id' => $item->id]));

        // DB内容チェック
        $this->assertDatabaseHas('items', [
            'id'          => $item->id,
            'name'        => '出品テスト',
            'price'       => 1980,
            'description' => '説明テキスト',
            'condition'   => '良好',
            'seller_id'   => $user->id,
            'status'      => 'for_sale',
        ]);

        // 画像パスと実ファイル存在
        $this->assertNotEmpty($item->image);
        $this->assertStringStartsWith('/storage/items/', $item->image);
        \Illuminate\Support\Facades\Storage::disk('public')
            ->assertExists(str_replace('/storage/', '', $item->image));

        // カテゴリ（検証)
        $this->assertSame($cats, $item->categories, 'categories array should be saved as-is');

        // 詳細ページの表示確認
        $show = $this->get(route('items.show', ['item_id' => $item->id]));
        $show->assertOk()
            ->assertSeeText('出品テスト')
            ->assertSeeText('説明テキスト')
            ->assertSeeText('良好')
            ->assertSeeText('ファッション')
            ->assertSeeText('アクセサリー');
    }
}

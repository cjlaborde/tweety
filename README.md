### Twitter Clone Setup
1. laravel new tweety --auth
2. Set up database in .env
3. Create new database with database tool
4. php artisan migrate
5. Create account
6. Now add tailwind
7. npm install tailwindcss --save-dev
8. add tailwind to laravel mix
9. create resources/css/main.css
10. Add tailwind to your css 
11. `https://tailwindcss.com/docs/installation`
12. Then npm install
13. npm run watch
14. Now visit public/css/main.css
15. Now import tailwind with views/layouts/app.blade.php
16. Remove app.css and use main.css since we don't want to use bootstrap
17. <link href="{{ asset('css/main.css') }}" rel="stylesheet">
18. now check site tweety.test and notice we lost the layout

### Design the Timeline
1. Get rid of all html in home.blade.php
2. Go to layout and remove code inside <div id="app">
3. Create public/images to save logo

### Make Timeline Dynamic
1. 'php artisan make:model Tweet -fmc` factory migration controller
2. write migration file
3. write TweetFactory
4. tinker factory('App\Tweet')->create(); to create tweet
```php
>>> factory('App\Tweet')->create();
=> App\Tweet {#3165
     user_id: 2,
     body: "Maiores qui eaque architecto tempora et consequatur.",
     updated_at: "2020-08-15 09:54:13",
     created_at: "2020-08-15 09:54:13",
     id: 1,
   }
```
5. Change tweet to dynamic in home.blade.php
6. Then pass the tweet to view from front end.
7. Set up relationship between User in Twitter model
8. Create 4 more tweets in tinker and hardcode user id factory('App\Tweet', 4)->create(['user_id' => 1]);
9. Apply unique id to fake avatar in pravatar.cc
10. We will set unique id equal to email address  src="https://i.pravatar.cc/50?u={{ $tweet->user->email }}" 
11. $tweets = Tweet::latest()->get(); latest basically defaults to 'created_at' and decending order.
12. Except we don't want all tweets we want user timeline.
13. You could create auth()->user()->timeline()
14. So go to user model and create timeline function.
15. lets generate 4 tweets not created by out used.
16. tinker  factory('App\Tweet', 4)->create();
17. They going to show up and they should not appear unless we following that user.
18. create a partial in User.php model called getAvatarAttribute the call it with auth()->user()->avatar in the view.
19. Now you only need to update getAvatarAttribute when you add actual avatar

### Expanding the Timeline
1. Delete HomeController and move index into TweetsController.php
2. In Login Controller to change where you go after log in.
3. Open LoginController.php and notice by default laravel redirect
4. protected $redirectTo = RouteServiceProvider::HOME;
5. click RouteServiceProvider::HOME
6. Go to /app/Providers/RouteServiceProvider.php
7. Change     public const HOME = '/home';
8. to public const HOME = '/tweets';
9. Visit without been logged in into http://tweety.test/tweets and get error
10. Call to a member function timeline() on null
11. so apply a Route::middlewate('auth')->group(function ()
12. Now if  I try to access /tweets without logged in, will be redirected to http://tweety.test/tweets
13. The Timeline with tweets created by the user.
14. In in User add another relationship tweet
15. Now in tinker App\User::First()->tweets we can get all tweets from user
16. Remove timeline() function in User.php
17. To include user's tweets
18. as well as the tweets of everyone that user follow
19. Order tweet in decending order by date
20.  Lets fetch all tweets from user user ideas of the user follows
21.  $user->follows->pluck('id') you can get all ideas user follows
22.  Now we can finish timeline() relationship in User.php model
23.  Create tweets with factory('App\Tweet')->create(['user_id => 5]); for user with id 5
24.  $ids = $this->follows->pluck('id'); problem is that if user follows 5000 people would show tweets from 5,000 users
25.  So we chain it like this  $ids = $this->follows()->pluck('id'); so we only get id and not a full collection of users.
26.  Which we then pluck the id.
27.  Now we do other fixes to timeline() in User.php model.

### Construct the Profile Page
1. In TweetsController index lets be more consistent    return view('tweets.index',
2. Then create tweets folder for the view
3. Since profile and tweets page have same layout
4. intead of writing same twice then add it to the layouts/app.blade.php
5. create route for profile
6. create route Route::get('/profiles/{user}', 'ProfileController@show');
7. then create controller ProfileControler
8. Now visit profile link but you need to change name to username later we add column or else would be strage to visit url with name
9. http://tweety.test/profiles/john Now you can see name on page.
10. But if you add function show(User $user) will not work since laravel will think name is the id.
11. So if we use 1 it will fail.
12. To fix this go to User.php model and overwrite getRouteKeyName() to return the name instead of id.
13. then return 'name'
14. to show you how it works.
15. in web.php at top put DB:listen(function ($query) { var_dump($query->sql, $query->bidings); };)
16. With this, it will send database queies in a var_dump
17. DB::listen(function ($query) { var_dump($query->sql, $query->bindings); });
18. then you comment ProfileController return and getRouteKeyName in User.php method
19. Then you will see how getRouteKeyName works behind the scenes it works.
20. 'select * from `users` where `id` = ? limit 1' array (size=1) 0 => string 'john' (length=4)
21. Now uncomment getRouteKeyName() in User.php
22. See the output changes, now it looks for name  'select * from `users` where `name` = ? limit 1'
23. Now in ProfilesController return the view with user
24. npx tailwindcss init --full to modify default tailwind

### Nested Layout Files with Components
1. Now we have problem since layout file assumes you are signed in.
2. That is why you can't access http://tweety.test/register views/auth/register.blade.php
3. create a file for layout views/components/master.blade.php
4. Then add a {{ $slot }} inside
5. Now we create views/components/app.blade.php
6. Then use <x-master> master been the template name, now everything inside x-master will be slotted with {{ $slot }}
7. Then slot in the default content for layout component {{ $slot }}
8. In register.blade.php replace
```php
@extends('layouts.app')

@section('content')
```
9. With <x-master></x-master> check http://tweety.test/register to see that it does work
10. Now do the same with views/auth/login.blade.php
11. Now go to views/tweets/index.php and replace
```php
@extends('layouts.theme')

@section('content')
```
12. with <x-app></x-app>
13. Then as good practice have single root element so put them into a div
```php
<x-app>
    <div>
        @include ('_publish-tweet-panel')
        
        @include ('_timeline')
    </div>
</x-app>
```
14. Now you have multiple layout files that require the same partials
15. Change tweets/index.php as well.
















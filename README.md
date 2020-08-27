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
    
### Build the Follow Form
1. Go to user.php and create method to check if user following()
2. When we have multiple methods in a model is usually good idea to extract it to a trait

#### Create Trait
1. Create trait in User.php called Followable in appl/Followable.php
2. Move follow, follows and following into Follable.php
3. Now User.php model has nothing related to following

#### laravel 7 web.php
1. In laravel 7 you can remove
```php
    public function getRouteKeyName()
    {
        // return parent::getRouteKeyName(); // TODO: Charge the autogenerated stub
        return 'name';
    }
```
2. You will get error but you can go to web.php and add it directly
3. `Route::get('/profiles/{user:name}', 'ProfilesController@show')->name('profile');`
4. Cleaning User.php model is very important since for every application is the first thing that become a cut object
5. Because all these functionally seems to make sense in User model.
6. Just be careful because it grows and grows over the years.
7. Create new route in web.php Route::post('/profiles/{user:name}/follow', 'FollowsController@store');
8. then create FollowsController php artisan make:controller FollowsController
9. create store() method inside FollowsController to follow user
10. Remove from database relationship
11. Follow now by clicking follow button in profile page
12. Now modify button to check if user is following or not.
13. Now go to Followable.php trait and create following() method
14. return $this->follows->contains($user);
15. downside is you fetching a collection
16. Problem with this is user loads follows collection of the entire relationship
17. Now what would happen if I followed 3000 people
18. Instead we can do a query and check if record exist
19. return $this->follows()->where('following_user_id', $user->id)->exists();
20. Now to set up unfollow and unfollow you can send same post request to toggle
21. Or you could create 2 different forms
22. We going to just toggle the follow
23. Create unfollow method in Follwable.php trait  return `$this->follows()->detach($user);`
24. Create toogleFollow method in trait Followable
25. Go to profiles/show.blade.php extract form and create component for follow form.
26. components/follow-button 
27. Is not going to work since there it needs user so you need to pass it.
28. <x-follow-button :user="$user"></x-follow-button> you need to add the : before user or it will fail
29. Create path() method in User.php to use it on _tweet.blade.php
```php
    public function path()
    {
        return route('profile', $this->name);
    }
```
30. Improve show.blade.php css
31. move display image to top div and add relative
32. then we want image again in center so we can use left cal 50% - 75px; (50% is half) (-75px is one half of the image)
33. You can also use transform to create same result left 50%; transform: translateX(-50) of the width
34. We can do same thing with Y which I we want to push it down 50% of the image translateY(-50%);
35. You can get same results using tailwind transform -translate-x-1/2 translate-y-1/2
36. style="left: 50%" width="150"
37. Notice you get double the border in _tweet.blade.php now so to solve it remove the border  border-b border-gray-400
38. Now you lose the line to solve this.
39. but now removes all border
40. a better way is using blade $loop-> last condition
41. <div class="flex p-4 {{$loop->last ? '' : 'border-b border-gray-400' }}">
42. Now we get proper borders but not on the last one
43. Now if you try to add a tweet it fails with 404
44. To fix it go to TweetsController store method and change reditect to `return redirect()->route('home');`
45. Notice that latest tweet in bottom fix this by adding order in User.php tweets() method
46. `return $this->hasMany(Tweet::class)->latest();`




















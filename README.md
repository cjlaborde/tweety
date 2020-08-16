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


> Dude has like 6 years of experience, but knowledge level is that of a junior dev
@Kramer#8335 
I had worse situation, I had prospet owner of a marketing agency who have no idea about Marketing and  I have to them teach how to run their damn business. Made terrible decisions and blew the budged on outsourcing marketing to multiple people, to do the marketing for them, when the site was not even built and they only wanted to use 20% of the budget in developing costs. When 
For a custom site they "sold" to their client. They wanted it done in 1 month fully operational when it was a complex 4-6 month project that would require more than 1 developer to produce.
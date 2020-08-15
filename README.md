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
19. 
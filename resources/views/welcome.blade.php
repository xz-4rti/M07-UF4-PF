<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Manager</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <login-form v-if="!isAuthenticated" @authenticated="onAuthenticated"></login-form>
        <product-table v-else :user="user"></product-table>
    </div>
</body>
</html>
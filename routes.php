<?php


$router->get('/', 'HomeController@index');

// Biblioteca
$router->get('/libros', 'LibroController@index');
$router->get('/libro', 'LibroController@show');
$router->get('/libro/crear', 'LibroController@create');
$router->post('/libro', 'LibroController@store');
$router->delete('/libro', 'LibroController@destroy');

// Autores
$router->get('/autores', 'AutorController@index');
$router->get('/autor', 'AutorController@show');
$router->get('/autor/crear', 'AutorController@create');
$router->post('/autor', 'AutorController@store');
$router->delete('/autor', 'AutorController@destroy');

// Pokémon (PokeAPI)
$router->get('/pokemons', 'PokemonController@index');
$router->get('/pokemon', 'PokemonController@show');
$router->get('/pokemon/search', 'PokemonController@search');
$router->get('/pokemon/filter-by-type', 'PokemonController@filterByType');

// API Tests - Pruebas de la integración PokeAPI
$router->get('/api/tests', 'ApiTestsController@index');
$router->get('/api/tests/get-pokemon-by-id', 'ApiTestsController@testGetPokemonById');
$router->get('/api/tests/search-pokemon', 'ApiTestsController@testSearchPokemon');
$router->get('/api/tests/get-pokemons', 'ApiTestsController@testGetPokemons');
$router->get('/api/tests/filter-by-type', 'ApiTestsController@testFilterByType');
$router->get('/api/tests/get-all-types', 'ApiTestsController@testGetAllTypes');

// Auth routes
//$router->get('/register', 'UserController@showRegisterForm');
//$router->post('/register', 'UserController@register');
//$router->get('/login', 'UserController@showLoginForm');
//$router->post('/login', 'UserController@login');
//$router->post('/logout', 'UserController@logout');
//
// Users
//$router->get('/users', 'UserController@listUsers');

// User Dashboard
//$router->get('/dashboard', 'UserController@showDashboard')->only('auth');
//$router->post('/dashboard/update', 'UserController@updateProfile')->only('auth');
//$router->post('/dashboard/delete', 'UserController@deleteAccount')->only('auth');
//

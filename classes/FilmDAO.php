<?php
/** File Created by u1755082 (Kieran Gateley) for module CIS2360 - Relational Databases and Web Integration */
include_once 'Film.php';

interface FilmDAO {

    public function getAllFilms() : array;

    public function getFilmsByRating(int $rating) : array;

    public function getFilmById(int $id) : Film;

    public function getRatingKey() : array;

    public function addStock(int $id, int $amount) : int;

    public function takeStock(int $id, int $amount) : int;

}

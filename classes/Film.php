<?php
/** File Created by u1755082 (Kieran Gateley) for module CIS2360 - Relational Databases and Web Integration */

class Film {

    private $id;
    private $name;
    private $description;
    private $rating;
    private $stock;
    private $cost;

    public function __construct(int $filmId, string $filmName, string $description, int $ratingId, int $stockCount, float $cost) {
        $this->id = $filmId;
        $this->name = $filmName;
        $this->description = $description;
        $this->rating = $ratingId;
        $this->stock = $stockCount;
        $this->cost = $cost;
    }

    final public function getId() : int { return $this->id; }

    final public function getName() : string { return $this->name; }

    final public function getDescription() : string { return $this->description; }

    final public function getRatingId() : string { return $this->rating; }

    final public function getStock() : string { return $this->stock; }

    final public function getCost() : string { return $this->cost; }

    final public function addStock() : int { $this->stock++; return $this->stock; }

    final public function takeStock() : int { $this->stock--; return $this->stock; }

}
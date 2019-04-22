<?php

class Film {

    private $id;
    private $name;
    private $description;
    private $rating;
    private $stock;

    public function __construct(int $filmId, string $filmName, string $description, int $ratingId, int $stockCount) {
        $this->id = $filmId;
        $this->name = $filmName;
        $this->description = $description;
        $this->rating = $ratingId;
        $this->stock = $stockCount;
    }

    final public function getId() : int { return $this->id; }

    final public function getName() : string { return $this->name; }

    final public function getDescription() : string { return $this->description; }

    final public function getRatingId() : string { return $this->rating; }

    final public function getStock() : string { return $this->stock; }

    final public function addStock() : int { $this->stock++; return $this->stock; }

    final public function takeStock() : int { $this->stock--; return $this->stock; }

}
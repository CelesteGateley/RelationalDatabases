<?php /** @noinspection MultiAssignmentUsageInspection */
include_once '../classes/FilmDAO.php';
include_once '../classes/Film.php';

class FilmDAOImpl implements FilmDAO {

    private $filmArray = array();
    private $ratingArray = array();
    private $db;

    protected $getFilmQuery = 'SELECT fss_film.filmid, fss_film.filmtitle, fss_film.filmdescription, fss_film.ratid, fss_dvdstock.stocklevel 
                               FROM fss_film, fss_dvdstock WHERE fss_film.filmid = fss_dvdstock.filmid AND fss_dvdstock.shopid = 1;';
    protected $getRatingQuery = 'SELECT ratid, filmrating FROM fss_rating;';

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
        $res = $this->db->query($this->getFilmQuery);
        foreach ($res as $row) {
            $film = new Film($row[0], $row[1], $row[2], $row[3], $row[4]);
            $this->filmArray[$row[0]] = $film;
        }
        $res = $this->db->query($this->getRatingQuery);
        foreach ($res as $rating) {
            $this->ratingArray[$rating[0]] = $rating[1];
        }
    }

    final public function getAllFilms(): array { return $this->filmArray; }

    final public function getFilmsByRating(int $rating): array {
        $retArray = array();
        foreach ($this->filmArray as $film) {
            if ($film->getRatingId() === $rating) { $retArray[] = $film; }
        }
        return $retArray;
    }

    final public function getFilmById(int $id): Film { return $this->filmArray[$id]; }

    final public function getRatingKey(): array { return $this->ratingArray; }

    final public function addStock(int $id, int $amount): int {
        for ($i = 0; $i < $amount; $i++) { $this->filmArray[$id]->addStock(); }
        $newStock = $this->filmArray[$id]->getStock();
        $this->updateStock($id, $newStock);
        return $newStock;
    }

    final public function takeStock(int $id, int $amount): int {
        for ($i = 0; $i < $amount; $i++) { $this->filmArray[$id]->takeStock(); }
        $newStock = $this->filmArray[$id]->getStock();
        $this->updateStock($id, $newStock);
        return $newStock;
    }

    private function updateStock(int $id, int $stock) : int {
        $stmt = $this->db->getPreparedStatement('UPDATE fss_dvdstock SET stocklevel = :stock WHERE filmid = :id AND shopid = 1;');
        $stmt->execute(['stock' => $stock, 'id' => $id]);
        return $stock;
    }
}
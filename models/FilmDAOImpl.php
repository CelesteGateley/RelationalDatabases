<?php /** @noinspection MultiAssignmentUsageInspection */
include_once '../classes/FilmDAO.php';
include_once '../classes/Film.php';

class FilmDAOImpl implements FilmDAO {

    private $filmArray = array();
    private $ratingArray = array();
    private $db;

    protected $getFilmQuery = 'SELECT fss_Film.filmid, fss_Film.filmtitle, fss_Film.filmdescription, fss_Film.ratid, fss_DVDStock.stocklevel 
                               FROM fss_Film, fss_DVDStock WHERE fss_Film.filmid = fss_DVDStock.filmid AND fss_DVDStock.shopid = 1;';
    protected $getRatingQuery = 'SELECT ratid, filmrating FROM fss_Rating;';

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
        $res = $this->db->query($this->getFilmQuery);
        foreach ($res as $row) {
            $film = new Film($row[0], $row[1], $row[2], $row[3], $row[4], 5.00);
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
<?php 

class PokeAPI
{

    protected $max = 807;
    protected $pokemon;
    protected $species;

    public function __construct($id = null)
    {
        $pokeapi = new \GuzzleHttp\Client();
        if ($id == null) {
            $id = random_int(1, $this->max);
        }
        $pokeid = $pokeapi->request('GET', 'https://pokeapi.co/api/v2/pokemon/'.$id);
        $pokeid = json_decode($pokeid->getBody());
        $this->pokemon = $pokeid;
    }

    public function getId()
    {
        return $this->pokemon->id;
    }

    public function getName()
    {
        return $this->pokemon->name;
    }

    public function getSpecies()
    {
        if (empty($this->species)) {
            $pokeapi = new \GuzzleHttp\Client();
            $pokespecies = $pokeapi->request('GET', 'https://pokeapi.co/api/v2/pokemon-species/'.$this->pokemon->id);
            $pokespecies = json_decode($pokespecies->getBody());
            $this->species = $pokespecies;
        }
        return $this->species;
    }

    public function getFlavorText($versions = false, $lang = "en") {
        if (empty($this->species)) {
            $this->getSpecies();
        }
        $flavorText = [];
        $flavorVersion = [];
        foreach (($this->species->flavor_text_entries) as $text) {
            if ($text->language->name == $lang) {
                if (in_array(preg_replace( "/\r|\n/", " ", $text->flavor_text), $flavorText)) {
                    $id = array_search(preg_replace( "/\r|\n/", " ", $text->flavor_text), $flavorText);
                    $flavorVersion[$id][] .= $text->version->name;
                } else {
                    $flavorText[] = preg_replace( "/\r|\n/", " ", $text->flavor_text);
                    $flavorVersion[] = [$text->version->name];
                }
            }
        }
        if ($versions === false) {
            return $flavorText;
        }
        $count = 0;
        $flavorValues = [];
        foreach ($flavorText as $text) {
            $flavorValues[] = ['version' => $flavorVersion[$count], 'text' => $text];
            $count++;
        }
        return $flavorValues;
    }
}

?>
<?php 

class PokeAPI
{

    protected $max = 807;
    protected $pokemon;
    protected $randomPokemon;
    protected $species;
    protected $speciesArray = [];
    protected $type = null;

    public function __construct($search = null, $type = null)
    {
        try {
            $pokeapi = new \GuzzleHttp\Client();
            if ($type == 'list') {
                $pokeid = $pokeapi->request('GET', 'https://pokeapi.co/api/v2/pokemon?limit=1000');
            } else if ($type == 'ran' || $type == null || ($search == null && $type == 'nid')) {
                if (!empty($search)) {
                    try {
                        $pokeid = $pokeapi->request('GET', 'https://pokeapi.co/api/v2/type/'.$search);
                        $randomType = true;
                        $this->type = $search;
                    } catch (Exception $e) {}
                }
                if (!isset($pokeid)) {
                    $randomType = false;
                    $ran = random_int(1, $this->max);
                    $pokeid = $pokeapi->request('GET', 'https://pokeapi.co/api/v2/pokemon/'.$ran);
                }
            } else if ($type == 'type') {
                $pokeid = $pokeapi->request('GET', 'https://pokeapi.co/api/v2/type/'.$search);
                $this->type = $type;
            } else if ($type == 'nid') {
                $pokeid = $pokeapi->request('GET', 'https://pokeapi.co/api/v2/pokemon/'.$search);
            } else if ($type == 'set') {
                $this->type = 'type';
                $count = 0;
                foreach ($search as $pokemon) {
                    $this->pokemon[] = $pokemon;
                    $this->speciesArray[] = $count;
                    $count++;
                }
            }
            if (isset($pokeid)) {
                $pokeid = json_decode($pokeid->getBody());
                if ($type == 'list') {
                    $this->pokemon = $pokeid->results;
                    $this->arrangePokemon();
                } else if ($type == 'ran' || $type == null || ($search == null && $type == 'nid')) {
                    if ($randomType) {
                        $this->pokemon = $pokeid->pokemon;
                        $this->arrangePokemon();
                        $this->randomPokemon = $this->pokemon;
                        $this->pokemon = null;
                        $this->randomPokemon();
                    } else {
                        $this->pokemon = $pokeid;
                    }
                } else if ($type == 'type') {
                    $this->pokemon = $pokeid->pokemon;
                    $this->arrangePokemon();
                } else {
                    $this->pokemon = $pokeid;
                }
            } else if ($type == 'set') {
            }
        } catch (Exception $e) {
            $this->pokemon = false;
        }
    }

    public function getId($id = 0)
    {
        if ($this->type == 'set') {
            return $this->speciesArray[$id]['id'];
        }
        if (is_array($this->pokemon)) {
            return $this->pokemon[$id]['id'];
        }
        return $this->pokemon->id;
    }

    public function getName($id = 0)
    {   
        if (is_array($this->pokemon)) {
            return $this->pokemon[$id]['name'];
        }
        return $this->pokemon->name;
    }

    public function getPokemon()
    {
        return $this->pokemon;
    }

    public function getRandomPokemonFull()
    {
        return $this->randomPokemonFull;        
    }

    public function getType()
    {
        return $this->type;
    }

    public function getSpeciesArray()
    {
        return $this->speciesArray;
    }

    public function getImage($id = 0) {
        $imgTest = new \GuzzleHttp\Client();
        try {
            $response = $imgTest->request('GET', "https://img.pokemondb.net/artwork/".$this->getName($id).".jpg");
            if ($response->getStatusCode() == 200) {
                return "https://img.pokemondb.net/artwork/".$this->getName($id).".jpg";
            }
        } catch (Exception $e) {}
        try {
            $response = $imgTest->request('GET', "https://img.pokemondb.net/artwork/".$this->getName($id)."n.jpg");
            if ($response->getStatusCode() == 200) {
                return "https://img.pokemondb.net/artwork/".$this->getName($id)."n.jpg";
            }
        } catch (Exception $e) {}
        try {
            $response = $imgTest->request('GET', "https://img.pokemondb.net/artwork/vector/".$this->getName($id).".png");
            if ($response->getStatusCode() == 200) {
                return "https://img.pokemondb.net/artwork/vector/".$this->getName($id).".png";
            }
        } catch (Exception $e) {}
    }

    private function arrangePokemon() 
    {
        $pokeName = [];
        $count = 0;
        if (count($this->pokemon) > 400) {
            foreach ($this->pokemon as $pokemon) {
                $id = substr($pokemon->url, 34, -1);
                if (strpos($pokemon->name, 'totem') || strpos($pokemon->name, '-battle-bond') || strpos($pokemon->name, '-cosplay')) {
                } else {
                    $pokeName[$id] = ['name' => $pokemon->name, 'url' => $pokemon->url, 'id' => $id];
                    $this->speciesArray[] = $count;
                    $count++;
                }
            }
        } else {
        foreach ($this->pokemon as $pokemon) {
            $id = substr($pokemon->pokemon->url, 34, -1);
            if (strpos($pokemon->pokemon->name, 'totem') || strpos($pokemon->pokemon->name, '-battle-bond') || strpos($pokemon->pokemon->name, '-cosplay')) {
            } else {
                $pokeName[] = ['name' => $pokemon->pokemon->name, 'url' => $pokemon->pokemon->url, 'id' => $id];
                $this->speciesArray[] = $count;
                $count++;
            }
        }
        }
        $this->pokemon = $pokeName;
    }

    public function randomPokemon()
    {
        $ran = random_int(1, count($this->randomPokemon));
        if (!isset($this->randomPokemonFull[$ran])) {
            $randomId = $this->randomPokemon[$ran-1]['id'];
            try {
                $pokeapi = new \GuzzleHttp\Client();
                $pokemon = $pokeapi->request('GET', 'https://pokeapi.co/api/v2/pokemon/'.$randomId);
            } catch (Exception $e) {
                return false;
            }
            if (isset($pokemon)) {
                $pokemon = json_decode($pokemon->getBody());
                $this->randomPokemonFull[$ran] = $pokemon;
            }
        }
        $this->pokemon = $this->randomPokemonFull[$ran];
    }

    public function getSpecies($id)
    {
        if (is_array($this->pokemon)) {
            if($this->speciesArray[$id] === $id) {
                $pokeapi = new \GuzzleHttp\Client();
                try {
                    $pokeSpecies = $pokeapi->request('GET', 'https://pokeapi.co/api/v2/pokemon-species/'.$this->pokemon[$id]['id']);
                } catch (Exception $e) {}
                if (!isset($pokeSpecies)) {
                    try {                           
                        $name = substr($this->pokemon[$id]['name'], 0, strpos($this->pokemon[$id]['name'], '-'));
                        $pokeSpecies = $pokeapi->request('GET', 'https://pokeapi.co/api/v2/pokemon-species/'.$name);
                    } catch (Exception $e) {}         
                }
                $pokeSpecies = json_decode($pokeSpecies->getBody());
                $this->speciesArray[$id] = $pokeSpecies;
                return $pokeSpecies;  
            } else {
                return $this->speciesArray[$id];
            }
        } else if (empty($this->species)) {
            $pokeapi = new \GuzzleHttp\Client();
            try {
                $pokeSpecies = $pokeapi->request('GET', 'https://pokeapi.co/api/v2/pokemon-species/'.$this->pokemon->id);
            } catch (Exception $e) {}
            if (!isset($pokeSpecies)) {
                try {
                    $name = substr($this->pokemon->name, 0, strpos($this->pokemon->name, '-'));
                    $pokeSpecies = $pokeapi->request('GET', 'https://pokeapi.co/api/v2/pokemon-species/'.$name);
                } catch (Exception $e) {}
            }
            $pokeSpecies = json_decode($pokeSpecies->getBody());
            $this->species = $pokeSpecies;
        }
        return $this->species;
    }

    public function getFlavorText($versions = false, $id = 0, $lang = "en")
    {   
        $this->getSpecies($id);
        if (is_array($this->pokemon)) {
            $flavorText = $this->getFlavorTextEntries($this->speciesArray[$id], $versions, $lang);
        } else {
            $flavorText = $this->getFlavorTextEntries($this->species, $versions, $lang);
        }
        return $flavorText;
    }

    private function getFlavorTextEntries($pokemon, $versions, $lang) 
    {
        $flavorText = [];
        $flavorVersion = [];
        foreach ($pokemon->flavor_text_entries as $text) {
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
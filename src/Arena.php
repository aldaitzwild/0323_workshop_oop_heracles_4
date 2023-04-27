<?php

namespace App;

use Exception;

class Arena 
{
    private array $monsters;
    private Hero $hero;

    private int $size = 10;

    public function __construct(Hero $hero, array $monsters)
    {
        $this->hero = $hero;
        $this->monsters = $monsters;
    }

    public function getDistance(Fighter $startFighter, Fighter $endFighter): float
    {
        $Xdistance = $endFighter->getX() - $startFighter->getX();
        $Ydistance = $endFighter->getY() - $startFighter->getY();
        return sqrt($Xdistance ** 2 + $Ydistance ** 2);
    }

    public function touchable(Fighter $attacker, Fighter $defenser): bool 
    {
        return $this->getDistance($attacker, $defenser) <= $attacker->getRange();
    }

    public function move(Fighter $fighter, string $direction): void
    {
        $x = $fighter->getX();
        $y = $fighter->getY();

        if($direction === 'N') $y--;
        else if($direction === 'S') $y++;
        else if($direction === 'W') $x--;
        else if($direction === 'E') $x++;

        if(
            $x < 0 || $x > 9 || $y < 0 || $y > 9
        ) {
            throw new Exception("Vous quittez la zone de mission !");
        }

        $monsters = $this->getMonsters();
        foreach($monsters as $monster) {
            if(
                $monster->getX() == $x
                && $monster->getY() == $y) {
                    throw new Exception("Vous ne pouvez pas chevaucher un monstre !");
                }
        }

        $fighter->setX($x);
        $fighter->setY($y);

    }

    public function battle(int $id): void
    {
        $monster = $this->monsters[$id];
        $hero = $this->hero;

        if($this->touchable($hero, $monster))
            $hero->fight($monster);

        if(!$monster->isAlive()) {
            unset($this->monsters[$id]);
            return;
        }

        if($this->touchable($monster, $hero))
            $monster->fight($hero);
    }

    /**
     * Get the value of monsters
     */ 
    public function getMonsters(): array
    {
        return $this->monsters;
    }

    /**
     * Set the value of monsters
     *
     */ 
    public function setMonsters($monsters): void
    {
        $this->monsters = $monsters;
    }

    /**
     * Get the value of hero
     */ 
    public function getHero(): Hero
    {
        return $this->hero;
    }

    /**
     * Set the value of hero
     */ 
    public function setHero($hero): void
    {
        $this->hero = $hero;
    }

    /**
     * Get the value of size
     */ 
    public function getSize(): int
    {
        return $this->size;
    }
}
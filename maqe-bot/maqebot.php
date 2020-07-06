<?php

class Mover {
    const NORTH = 0;
    const EAST = 1;
    const SOUTH = 2;
    const WEST = 3;

    const DIRECTION = ['North', 'East', 'South', 'West'];

    private $x; 
    private $y;
    private $direction;

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }

    public function getDirection(): int
    {
        return $this->direction;
    }

    public function __construct(int $x = 0, int $y = 0, int $direction = 0)
    {
        $this->x = $x;
        $this->y = $y;
        $this->direction = $direction;
    }

    public function turnRight(): Mover 
    {
        $turn = $this->direction + 1;
        $this->direction = $turn > self::WEST ? self::NORTH : $turn;
        return $this;
    }

    public function turnLeft(): Mover
    {
        $turn = $this->direction - 1;
        $this->direction = $turn < self::NORTH ? self::WEST : $turn;
        return $this;
    }

    public function print()
    {
        return 'X: '.$this->x.' Y: '.$this->y.' Direction: '.Mover::DIRECTION[$this->direction];
    }

    public function walk(int $step = 1): Mover
    {
        switch ($this->direction) {
            case self::NORTH;
                $this->y += $step;
                break;
            case self::EAST;
                $this->x += $step;
                break;
            case self::SOUTH;
                $this->y -= $step;
                break;
            case self::WEST;
                $this->x -= $step;
                break;
        }
        return $this;
    }

}

function command(array $operations)
{
    $mover = new Mover();
    
    foreach ($operations as $operation) {
        $action = ucfirst($operation[0][0]);
        if ($action === 'R') {
            $mover->turnRight();
        } elseif ($action === 'L') {
            $mover->turnLeft();
        } elseif ($action === 'W') {
            $mover->walk($operation[1] ?? 1);
        }
        elseif (trim($action) !== '') {
            throw new \InvalidArgumentException("Invalid operation `{$operation[0]}`");
        }
    }
    
    echo $mover->print().PHP_EOL;
}

preg_match_all('/R|L|W(\d+)|.+/i', trim($_SERVER['argv'][1] ?? ''), $operations, PREG_SET_ORDER);
try {
    command($operations ?: []);
} catch (\Throwable $e) {
    echo $e->getMessage(), PHP_EOL;
    exit(1);
}
?>
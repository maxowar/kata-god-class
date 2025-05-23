<?php

class SpaceshipGodClass
{
    // --- Core Ship Attributes ---
    public string $name;
    public string $shipClass;
    public float $currentHullPoints;
    public float $maxHullPoints;
    public float $currentShieldPoints;
    public float $maxShieldPoints;
    public bool $isShieldsActive = false;

    // --- Crew & Cargo ---
    public array $crewMembers = [];
    public int $maxCrewCapacity;
    public array $passengerList = [];
    public array $cargoHold = []; // item_name => quantity
    public float $currentCargoWeight = 0;
    public float $cargoCapacity;

    // --- Propulsion & Navigation ---
    public float $fuelLevel;
    public float $fuelCapacity;
    public string $engineType;
    public bool $isEngineActive = false;
    public float $currentSpeed = 0;
    public float $maxWarpFactor = 9.9;
    public float $currentWarpFactor = 0;
    public array $currentLocation = ['x' => 0, 'y' => 0, 'z' => 0];
    public ?array $destination = null;
    public string $navigationComputerStatus = "Online";
    public string $starChartsVersion = "v2.5. galactic-patch";
    public bool $autopilotEngaged = false;

    // --- Combat ---
    public array $weaponSystems;
    public array $weaponPowerLevels = [];
    public ?string $targetLock = null;
    public bool $isInCombat = false;
    public array $ammunitionCount = ["Phasers" => 1000, "Photon Torpedoes" => 50];

    // --- Sensors & Communication ---
    public string $sensorSuite;
    public ?string $longRangeScanResults = null;
    public ?string $shortRangeScanResults = null;
    public string $commsChannel = "Open Hailing Frequencies";
    public bool $isCommsEncrypted = false;
    public array $incomingMessages = [];

    // --- Life Support & Environment ---
    public string $lifeSupportLevel;
    public float $oxygenLevel = 98;
    public float $internalTemperature = 22;
    public string $gravityPlatingStatus = "Active";
    public float $wasteRecyclingEfficiency = 85;

    // --- Miscellaneous ---
    public string $coffeeMachineModel;
    public float $coffeeLevel = 100;
    public bool $isHolodeckInUse = false;
    public ?string $holodeckProgramRunning = null;
    public array $shipsLog = [];
    public bool $selfDestructSequenceInitiated = false;
    public string $selfDestructCode = "000-DESTRUCT-0";
    public string $insurancePolicyNumber = "GCS-1701-OMG";

    public function __construct(
        string $name, string $shipClass, float $maxHullPoints, float $maxShieldPoints,
        int $maxCrewCapacity, float $cargoCapacity, float $fuelCapacity,
        string $engineType, array $weaponSystems, string $sensorSuite,
        string $lifeSupportLevel, string $coffeeMachineModel
    ) {
        echo "Constructing the mighty {$name}, a {$shipClass}-class vessel!\n";

        $this->name = $name;
        $this->shipClass = $shipClass;
        $this->currentHullPoints = $maxHullPoints;
        $this->maxHullPoints = $maxHullPoints;
        $this->currentShieldPoints = $maxShieldPoints;
        $this->maxShieldPoints = $maxShieldPoints;
        $this->maxCrewCapacity = $maxCrewCapacity;
        $this->cargoCapacity = $cargoCapacity;
        $this->fuelLevel = $fuelCapacity;
        $this->fuelCapacity = $fuelCapacity;
        $this->engineType = $engineType;
        $this->weaponSystems = $weaponSystems;
        foreach ($weaponSystems as $weapon) {
            $this->weaponPowerLevels[$weapon] = 100;
        }
        $this->sensorSuite = $sensorSuite;
        $this->lifeSupportLevel = $lifeSupportLevel;
        $this->coffeeMachineModel = $coffeeMachineModel;

        echo "{$name} construction complete. All systems... probably online.\n";
        $this->logEntry("Ship constructed.");
    }

    // --- Logging ---
    public function logEntry(string $message): void
    {
        $timestamp = date("c"); // ISO 8601 format
        $logMessage = "[{$timestamp}] - {$message}";
        $this->shipsLog[] = $logMessage;
        echo "Log: {$message}\n";
    }

    // --- Hull & Shields ---
    public function takeDamage(float $amount): void
    {
        if (!$this->isShieldsActive || $this->currentShieldPoints <= 0) {
            $this->currentHullPoints -= $amount;
            $this->logEntry("Took {$amount} hull damage.");
            if ($this->currentHullPoints <= 0) {
                $this->logEntry("HULL BREACH! SHIP DESTROYED!");
                $this->currentHullPoints = 0;
                echo "💥💥💥 BOOM! 💥💥💥\n";
            }
        } else {
            $shieldDamage = min($this->currentShieldPoints, $amount);
            $this->currentShieldPoints -= $shieldDamage;
            $remainingDamage = $amount - $shieldDamage;
            $this->logEntry("Shields absorbed {$shieldDamage} damage.");
            if ($remainingDamage > 0) {
                $this->currentHullPoints -= $remainingDamage;
                $this->logEntry("Took {$remainingDamage} hull damage through shields.");
            }
            if ($this->currentShieldPoints <= 0) {
                $this->logEntry("Shields are down!");
                $this->isShieldsActive = false;
            }
        }
    }

    public function repairHull(float $amount): void
    {
        $this->currentHullPoints = min($this->maxHullPoints, $this->currentHullPoints + $amount);
        $this->logEntry("Hull repaired by {$amount} points. Current: {$this->currentHullPoints}");
    }

    public function toggleShields(bool $state): void
    {
        if ($state && $this->currentShieldPoints > 0) {
            $this->isShieldsActive = true;
            $this->logEntry("Shields up!");
        } else {
            $this->isShieldsActive = false;
            $this->logEntry("Shields down!");
        }
    }

    public function rechargeShields(float $amount): void
    {
        $this->currentShieldPoints = min($this->maxShieldPoints, $this->currentShieldPoints + $amount);
        $this->logEntry("Shields recharged to {$this->currentShieldPoints}.");
    }

    // --- Crew & Cargo Management ---
     public function addCrewMember(string $name, string $position): bool
     {
        if (count($this->crewMembers) < $this->maxCrewCapacity) {
            $this->crewMembers[] = ["name" => $name, "position" => $position];
            $this->logEntry("Crew member {$name} ({$position}) has come aboard.");
            return true;
        } else {
            $this->logEntry("Cannot add crew, capacity reached.");
            return false;
        }
     }

     public function loadCargo(string $itemName, int $quantity, float $weightPerUnit): bool
     {
        $totalWeight = $quantity * $weightPerUnit;
        if ($this->currentCargoWeight + $totalWeight <= $this->cargoCapacity) {
            $this->cargoHold[$itemName] = ($this->cargoHold[$itemName] ?? 0) + $quantity;
            $this->currentCargoWeight += $totalWeight;
            $this->logEntry("Loaded {$quantity} units of {$itemName}.");
            return true;
        } else {
            $this->logEntry("Cannot load {$itemName}, insufficient cargo space.");
            return false;
        }
     }

    // --- Engine & Navigation ---
    public function startEngine(): void
    {
        if ($this->fuelLevel > 0) {
            $this->isEngineActive = true;
            $this->logEntry("Engines started.");
        } else {
            $this->logEntry("Cannot start engines, no fuel.");
        }
    }

    public function stopEngine(): void
    {
        $this->isEngineActive = false;
        $this->currentSpeed = 0;
        $this->currentWarpFactor = 0;
        $this->logEntry("Engines stopped.");
    }

     public function engageWarp(float $factor): void
     {
        if ($this->isEngineActive && $factor <= $this->maxWarpFactor) {
            $this->currentWarpFactor = $factor;
            $this->currentSpeed = 0;
            $this->logEntry("Warp factor {$factor} engaged!");
        } elseif (!$this->isEngineActive) {
            $this->logEntry("Cannot engage warp, engines offline.");
        } else {
            $this->logEntry("Cannot engage warp factor {$factor}, exceeds maximum.");
        }
     }

     public function setCourse(float $x, float $y, float $z): void
     {
        $this->destination = ['x' => $x, 'y' => $y, 'z' => $z];
        $this->logEntry("Course set for coordinates: ({$x}, {$y}, {$z}).");
     }

    // ... (Other methods would be translated similarly) ...

    // --- Coffee & Holodeck ---
    public function brewCoffee(string $strength = "Strong"): ?string
    {
        if ($this->coffeeLevel > 5) {
            $this->coffeeLevel -= 5;
            $this->logEntry("Brewing a {$strength} coffee. Ahh, essential for deep space travel.");
            return "☕";
        } else {
            $this->logEntry("CRITICAL ERROR: Coffee depleted! Morale dropping!");
            return null;
        }
    }

    // --- Status Report ---
    public function getFullStatusReport(): void
    {
        echo "\n" . str_repeat("=", 50) . "\n";
        echo "       STATUS REPORT: {$this->name}       \n";
        echo str_repeat("=", 50) . "\n";
        echo "Class: {$this->shipClass}\n";
        echo "Hull: {$this->currentHullPoints}/{$this->maxHullPoints}\n";
        $shieldsActive = $this->isShieldsActive ? 'Active' : 'Inactive';
        echo "Shields: {$this->currentShieldPoints}/{$this->maxShieldPoints} (Active: {$shieldsActive})\n";
        echo "Crew: " . count($this->crewMembers) . "/{$this->maxCrewCapacity}\n";
        echo "Cargo: {$this->currentCargoWeight}/{$this->cargoCapacity} tons\n";
        echo "Fuel: " . number_format($this->fuelLevel, 2) . "/{$this->fuelCapacity}\n";
        $engineActive = $this->isEngineActive ? 'Active' : 'Inactive';
        echo "Engines: {$this->engineType} (Active: {$engineActive})\n";
        echo "Speed: Warp {$this->currentWarpFactor} / Impulse {$this->currentSpeed}\n";
        echo "Location: ({$this->currentLocation['x']}, {$this->currentLocation['y']}, {$this->currentLocation['z']})\n";
        $dest = $this->destination ? "({$this->destination['x']}, {$this->destination['y']}, {$this->destination['z']})" : "None";
        echo "Destination: {$dest}\n";
        echo "Weapons: " . implode(', ', $this->weaponSystems) . "\n";
        $target = $this->targetLock ?? "None";
        echo "Target: {$target}\n";
        echo "Life Support: {$this->lifeSupportLevel} (O2: {$this->oxygenLevel}%, Temp: {$this->internalTemperature}°C)\n";
        echo "Coffee Level: {$this->coffeeLevel}%\n";
        $holodeck = $this->isHolodeckInUse ? 'In use' : 'Idle';
        echo "Holodeck: {$holodeck}\n";
        $selfDestruct = $this->selfDestructSequenceInitiated ? 'ARMED' : 'Safe';
        echo "Self Destruct: {$selfDestruct}\n";
        echo str_repeat("=", 50) . "\n\n";
    }
}

// --- Example Usage ---
$myShip = new SpaceshipGodClass(
    "The Colossus PHP",
    "Server-Side-Dreadnought",
    5000, 10000, 1015, 50000, 100000,
    "PHP-Powered Warp Drive",
    ["Phasers", "Photon Torpedoes", "SQL Injectors"], // Beware!
    "Zend Engine Sensor Array",
    "Session-Based",
    "Nespresso Pro"
);

$myShip->addCrewMember("Rasmus Lerdorf", "Captain");
$myShip->addCrewMember("Zeev Suraski", "First Officer");
$myShip->loadCargo("Frameworks", 500, 5);
$myShip->toggleShields(true);
$myShip->startEngine();
$myShip->setCourse(100, 250, -50);
$myShip->engageWarp(7);
$myShip->takeDamage(3500);
$myShip->brewCoffee("Espresso");
$myShip->getFullStatusReport();


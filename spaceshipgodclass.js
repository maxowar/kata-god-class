class SpaceshipGodClass {
    /**
     * Behold! The JavaScript version of our God Class - our Spaceship!
     * It does *everything*, now with more 'this' and prototypes (under the hood)!
     */

    constructor(name, shipClass, maxHullPoints, maxShieldPoints,
                maxCrewCapacity, cargoCapacity, fuelCapacity,
                engineType, weaponSystems, sensorSuite,
                lifeSupportLevel, coffeeMachineModel) {
        console.log(`Constructing the mighty ${name}, a ${shipClass}-class vessel!`);

        // --- Core Ship Attributes ---
        this.name = name;
        this.shipClass = shipClass;
        this.currentHullPoints = maxHullPoints;
        this.maxHullPoints = maxHullPoints;
        this.currentShieldPoints = maxShieldPoints;
        this.maxShieldPoints = maxShieldPoints;
        this.isShieldsActive = false;

        // --- Crew & Cargo ---
        this.crewMembers = [];
        this.maxCrewCapacity = maxCrewCapacity;
        this.passengerList = [];
        this.cargoHold = {}; // item_name: quantity
        this.currentCargoWeight = 0;
        this.cargoCapacity = cargoCapacity;

        // --- Propulsion & Navigation ---
        this.fuelLevel = fuelCapacity;
        this.fuelCapacity = fuelCapacity;
        this.engineType = engineType;
        this.isEngineActive = false;
        this.currentSpeed = 0;
        this.maxWarpFactor = 9.9;
        this.currentWarpFactor = 0;
        this.currentLocation = { x: 0, y: 0, z: 0 };
        this.destination = null;
        this.navigationComputerStatus = "Online";
        this.starChartsVersion = "v2.5. galactic-patch";
        this.autopilotEngaged = false;

        // --- Combat ---
        this.weaponSystems = weaponSystems;
        this.weaponPowerLevels = {};
        weaponSystems.forEach(weapon => {
            this.weaponPowerLevels[weapon] = 100;
        });
        this.targetLock = null;
        this.isInCombat = false;
        this.ammunitionCount = { "Phasers": 1000, "Photon Torpedoes": 50 };

        // --- Sensors & Communication ---
        this.sensorSuite = sensorSuite;
        this.longRangeScanResults = null;
        this.shortRangeScanResults = null;
        this.commsChannel = "Open Hailing Frequencies";
        this.isCommsEncrypted = false;
        this.incomingMessages = [];

        // --- Life Support & Environment ---
        this.lifeSupportLevel = lifeSupportLevel;
        this.oxygenLevel = 98;
        this.internalTemperature = 22;
        this.gravityPlatingStatus = "Active";
        this.wasteRecyclingEfficiency = 85;

        // --- Miscellaneous ---
        this.coffeeMachineModel = coffeeMachineModel;
        this.coffeeLevel = 100;
        this.isHolodeckInUse = false;
        this.holodeckProgramRunning = null;
        this.shipsLog = [];
        this.selfDestructSequenceInitiated = false;
        this.selfDestructCode = "000-DESTRUCT-0";
        this.insurancePolicyNumber = "GCS-1701-OMG";

        console.log(`${name} construction complete. All systems... probably online.`);
        this.logEntry("Ship constructed.");
    }

    // --- Logging ---
    logEntry(message) {
        const timestamp = new Date().toISOString();
        const logMessage = `[${timestamp}] - ${message}`;
        this.shipsLog.push(logMessage);
        console.log(`Log: ${message}`);
    }

    // --- Hull & Shields ---
    takeDamage(amount) {
        if (!this.isShieldsActive || this.currentShieldPoints <= 0) {
            this.currentHullPoints -= amount;
            this.logEntry(`Took ${amount} hull damage.`);
            if (this.currentHullPoints <= 0) {
                this.logEntry("HULL BREACH! SHIP DESTROYED!");
                this.currentHullPoints = 0;
                console.log("💥💥💥 BOOM! 💥💥💥");
            }
        } else {
            const shieldDamage = Math.min(this.currentShieldPoints, amount);
            this.currentShieldPoints -= shieldDamage;
            const remainingDamage = amount - shieldDamage;
            this.logEntry(`Shields absorbed ${shieldDamage} damage.`);
            if (remainingDamage > 0) {
                this.currentHullPoints -= remainingDamage;
                this.logEntry(`Took ${remainingDamage} hull damage through shields.`);
            }
            if (this.currentShieldPoints <= 0) {
                this.logEntry("Shields are down!");
                this.isShieldsActive = false;
            }
        }
    }

    repairHull(amount) {
        this.currentHullPoints = Math.min(this.maxHullPoints, this.currentHullPoints + amount);
        this.logEntry(`Hull repaired by ${amount} points. Current: ${this.currentHullPoints}`);
    }

    toggleShields(state) {
        if (state && this.currentShieldPoints > 0) {
            this.isShieldsActive = true;
            this.logEntry("Shields up!");
        } else {
            this.isShieldsActive = false;
            this.logEntry("Shields down!");
        }
    }

    rechargeShields(amount) {
        this.currentShieldPoints = Math.min(this.maxShieldPoints, this.currentShieldPoints + amount);
        this.logEntry(`Shields recharged to ${this.currentShieldPoints}.`);
    }

    // --- Crew & Cargo Management ---
    addCrewMember(name, position) {
        if (this.crewMembers.length < this.maxCrewCapacity) {
            this.crewMembers.push({ name: name, position: position });
            this.logEntry(`Crew member ${name} (${position}) has come aboard.`);
            return true;
        } else {
            this.logEntry("Cannot add crew, capacity reached.");
            return false;
        }
    }

    loadCargo(itemName, quantity, weightPerUnit) {
        const totalWeight = quantity * weightPerUnit;
        if (this.currentCargoWeight + totalWeight <= this.cargoCapacity) {
            this.cargoHold[itemName] = (this.cargoHold[itemName] || 0) + quantity;
            this.currentCargoWeight += totalWeight;
            this.logEntry(`Loaded ${quantity} units of ${itemName}.`);
            return true;
        } else {
            this.logEntry(`Cannot load ${itemName}, insufficient cargo space.`);
            return false;
        }
    }

    // --- Engine & Navigation ---
    startEngine() {
        if (this.fuelLevel > 0) {
            this.isEngineActive = true;
            this.logEntry("Engines started.");
        } else {
            this.logEntry("Cannot start engines, no fuel.");
        }
    }

    stopEngine() {
        this.isEngineActive = false;
        this.currentSpeed = 0;
        this.currentWarpFactor = 0;
        this.logEntry("Engines stopped.");
    }

    engageWarp(factor) {
        if (this.isEngineActive && factor <= this.maxWarpFactor) {
            this.currentWarpFactor = factor;
            this.currentSpeed = 0;
            this.logEntry(`Warp factor ${factor} engaged!`);
        } else if (!this.isEngineActive) {
            this.logEntry("Cannot engage warp, engines offline.");
        } else {
            this.logEntry(`Cannot engage warp factor ${factor}, exceeds maximum.`);
        }
    }

    setCourse(x, y, z) {
        this.destination = { x, y, z };
        this.logEntry(`Course set for coordinates: (${x}, ${y}, ${z}).`);
    }

    // ... (Other methods would be translated similarly) ...

    // --- Coffee & Holodeck ---
    brewCoffee(strength = "Strong") {
        if (this.coffeeLevel > 5) {
            this.coffeeLevel -= 5;
            this.logEntry(`Brewing a ${strength} coffee. Ahh, essential for deep space travel.`);
            return "☕";
        } else {
            this.logEntry("CRITICAL ERROR: Coffee depleted! Morale dropping!");
            return null;
        }
    }

    // --- Status Report ---
    getFullStatusReport() {
        console.log("\n" + "=".repeat(50));
        console.log(`       STATUS REPORT: ${this.name}       `);
        console.log("=".repeat(50));
        console.log(`Class: ${this.shipClass}`);
        console.log(`Hull: ${this.currentHullPoints}/${this.maxHullPoints}`);
        console.log(`Shields: ${this.currentShieldPoints}/${this.maxShieldPoints} (Active: ${this.isShieldsActive})`);
        console.log(`Crew: ${this.crewMembers.length}/${this.maxCrewCapacity}`);
        console.log(`Cargo: ${this.currentCargoWeight}/${this.cargoCapacity} tons`);
        console.log(`Fuel: ${this.fuelLevel.toFixed(2)}/${this.fuelCapacity}`);
        console.log(`Engines: ${this.engineType} (Active: ${this.isEngineActive})`);
        console.log(`Speed: Warp ${this.currentWarpFactor} / Impulse ${this.currentSpeed}`);
        console.log(`Location: (${this.currentLocation.x}, ${this.currentLocation.y}, ${this.currentLocation.z})`);
        const dest = this.destination ? `(${this.destination.x}, ${this.destination.y}, ${this.destination.z})` : "None";
        console.log(`Destination: ${dest}`);
        console.log(`Weapons: ${this.weaponSystems.join(', ')}`);
        console.log(`Target: ${this.targetLock || "None"}`);
        console.log(`Life Support: ${this.lifeSupportLevel} (O2: ${this.oxygenLevel}%, Temp: ${this.internalTemperature}°C)`);
        console.log(`Coffee Level: ${this.coffeeLevel}%`);
        console.log(`Holodeck: ${this.isHolodeckInUse ? 'In use' : 'Idle'}`);
        console.log(`Self Destruct: ${this.selfDestructSequenceInitiated ? 'ARMED' : 'Safe'}`);
        console.log("=".repeat(50) + "\n");
    }
}

// --- Example Usage ---
const myShipJS = new SpaceshipGodClass(
    "The Colossus JS",
    "Client-Side-Cruiser",
    5000, 10000, 1015, 50000, 100000,
    "V8-Powered Warp Core",
    ["Phasers", "Photon Torpedoes", "Console.Errors"], // Ouch!
    "DOM Inspector Array",
    "Event-Loop Based",
    "Keurig PodMaster"
);

myShipJS.addCrewMember("Brendan Eich", "Captain");
myShipJS.addCrewMember("Douglas Crockford", "First Officer");
myShipJS.loadCargo("Node Modules", 100000, 0.2); // Oh dear...
myShipJS.toggleShields(true);
myShipJS.startEngine();
myShipJS.setCourse(100, 250, -50);
myShipJS.engageWarp(7);
myShipJS.takeDamage(3500);
myShipJS.brewCoffee("Latte");
myShipJS.getFullStatusReport();

# God Class Kata

This exercise will highlight the problems with God Classes

🚀 Today, we're diving deep into an anti-pattern, something you'll want to understand so you can avoid it in your real-world projects. We're going to build a "God Class," a single, monstrous class that tries to do everything. Our subject? A Spaceship!

This exercise will highlight the problems with God Classes: they're hard to understand, difficult to maintain, a nightmare to test, and a breeding ground for bugs. But, by building one, you'll see exactly why we strive for principles like Single Responsibility and Separation of Concerns.

But, wait...are we sure they are so hard to understand, to test, to maintain? They are so pretty and looks so comfy sometimes.

# 1. Exercise - rewrite the code
----------------------------------

**Objective:** try to refactor following SOLID principles with you pair


## Optional

write the tests before

**Debrief:**

# 2. Excercise - The Curvature Conundrum
----------------------------------

**Objective:** Add the new "Curvature Engine" feature to the SpaceshipGodClass.

**The Technology:** The Curvature Engine doesn't use traditional fuel. Instead, it draws immense power from the ship's main **Tachyon Core** and generates a **Curvature Field**. The higher the field level, the faster the ship 'bends' space to its destination.

**Implementation Requirements:**

1.  **New Ship Attributes:** You need to add these _directly_ to the SpaceshipGodClass:
    
    *   tachyonCoreCharge: (float) Current charge level (starts at 100.0). Max 100.0.
        
    *   maxTachyonCoreCharge: (float) 100.0.
        
    *   isCurvatureDriveActive: (boolean) Is the new drive online? (Starts false).
        
    *   currentCurvatureLevel: (integer) From 0 (off) to 10 (max).
        
    *   maxCurvatureLevel: (integer) 10.
        
    *   curvatureFieldIntegrity: (float) Starts at 100.0. Bending space is stressful!
        
    *   lastCurvatureJumpTimestamp: (datetime/timestamp) To track cooldown.
        
2.  **New Methods:**
    
    *   engageCurvatureDrive(level): Attempts to activate the drive to a specific level (1-10).
        
        *   **Checks needed:**
            
            *   Cannot engage if tachyonCoreCharge is below 20.0.
                
            *   Cannot engage if curvatureFieldIntegrity is below 50.0.
                
            *   Cannot engage if isEngineActive (the _old_ engine) is true.
                
            *   Cannot engage if isShieldsActive is true (power conflict!).
                
            *   Cannot engage within 5 minutes (or 5 time units) of the lastCurvatureJumpTimestamp (cooldown).
                
            *   If successful, set isCurvatureDriveActive to true, set currentCurvatureLevel, and log.
                
            *   If not, log the _specific_ reason.
                
    *   disengageCurvatureDrive(): Sets isCurvatureDriveActive to false, currentCurvatureLevel to 0, and logs.
        
    *   rechargeTachyonCore(amount): Recharges the core, capped at maxTachyonCoreCharge.
        
    *   repairCurvatureField(amount): Repairs integrity, capped at 100.0.
        
3.  **The** _**Real**_ **Challenge - Modifying travel():** This is where the fun begins. You need to update (or replace/augment) the existing travel() method to handle Curvature Jumps.
    
    *   If isCurvatureDriveActive is true, the ship doesn't travel _linearly_ anymore. It performs a **Curvature Jump**.
        
    *   A Curvature Jump _instantly_ moves the ship a significant distance towards its destination.
        
    *   **The Curvature Jump Algorithm:**
        
        *   **Base Distance (Dbase​):** Dbase​=currentCurvatureLevel4×time\_delta×10
            
        *   **Mass Penalty (Pmass​):** The heavier the ship, the harder it is to bend space. Pmass​=1−(cargoCapacity×2currentCargoWeight​) (This means a fully loaded ship is less efficient). Ensure Pmass​ is at least 0.1.
            
        *   **Integrity Penalty (Pint​):** A weak field struggles. Pint​=(100.0curvatureFieldIntegrity​)0.5
            
        *   **Actual Distance (Dactual​):** Dactual​=Dbase​×Pmass​×Pint​
            
        *   **Tachyon Core Drain (Cdrain​):** Cdrain​=(currentCurvatureLevel2×time\_delta)+(100len(crewMembers)​) (Yes, more crew means more drain due to 'life support resonance' - don't ask, Star Command insisted!).
            
        *   **Field Stress (Sfield​):** Sfield​=currentCurvatureLevel×time\_delta×0.5
            
    *   **Executing the Jump:**
        
        *   Calculate Dactual​, Cdrain​, and Sfield​.
            
        *   If Cdrain​ > tachyonCoreCharge, the jump fails! Log it, disengage the drive, and set curvatureFieldIntegrity to 10.0 (catastrophic resonance cascade!).
            
        *   Otherwise:
            
            *   Move the ship Dactual​ units towards the destination (use the existing linear movement logic, but apply this _huge_ distance). Check if you've arrived.
                
            *   Decrease tachyonCoreCharge by Cdrain​.
                
            *   Decrease curvatureFieldIntegrity by Sfield​.
                
            *   Set lastCurvatureJumpTimestamp to the current time.
                
            *   Log the jump, distance covered, new charge, and new integrity.
                
            *   **Crucially**, immediately disengage the drive (Curvature Jumps are point-to-point, not continuous).
                
    *   Remember, the _original_ travel() logic for Warp/Impulse must still work if the Curvature Drive isn't active!
        

**Your Task:**

Implement all the above changes _within_ the SpaceshipGodClass. Do **not** create new classes (yet!). We want you to feel the _pain_.

**While You Work - Reflect on These:**

*   How many different parts of the SpaceshipGodClass did you have to look at or modify?
    
*   Did you find yourself scrolling up and down constantly?
    
*   Were you worried that changing the travel method might break the old Warp/Impulse functionality?
    
*   How many _different_ concerns (propulsion, power, cargo, crew, damage control) did this "single" new feature touch?
    
*   How easy was it to understand the travel method _before_ you even started adding the new code?
    
*   Imagine you _also_ had to add a "Cloaking Device" next. How would you feel?
    

**Debrief:**

Once you've attempted (or succeeded, if you're a masochist) to implement this, we will discuss our experiences. We'll pinpoint exactly _why_ this felt so difficult and messy, and then we'll explore how a better-designed system, following principles like SOLID, would make adding such features a _much_ more pleasant and safer experience.

Good luck, developers. 

May the odds (and your sanity) be ever in your favor!

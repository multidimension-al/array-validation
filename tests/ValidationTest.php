<?php
/**    __  ___      ____  _     ___                           _                    __
 *    /  |/  /_  __/ / /_(_)___/ (_)___ ___  ___  ____  _____(_)___  ____   ____ _/ /
 *   / /|_/ / / / / / __/ / __  / / __ `__ \/ _ \/ __ \/ ___/ / __ \/ __ \ / __ `/ / 
 *  / /  / / /_/ / / /_/ / /_/ / / / / / / /  __/ / / (__  ) / /_/ / / / // /_/ / /  
 * /_/  /_/\__,_/_/\__/_/\__,_/_/_/ /_/ /_/\___/_/ /_/____/_/\____/_/ /_(_)__,_/_/   
 *                                                                                  
 * CONFIDENTIAL
 *
 * © 2017 Multidimension.al - All Rights Reserved
 * 
 * NOTICE:  All information contained herein is, and remains the property of
 * Multidimension.al and its suppliers, if any.  The intellectual and
 * technical concepts contained herein are proprietary to Multidimension.al
 * and its suppliers and may be covered by U.S. and Foreign Patents, patents in
 * process, and are protected by trade secret or copyright law. Dissemination
 * of this information or reproduction of this material is strictly forbidden
 * unless prior written permission is obtained.
 */

namespace Multidimensional\ArrayValidation\Test;

use Multidimensional\ArrayValidation\Validation;
use PHPUnit\Framework\TestCase;

class ValidationTest extends TestCase
{
     public $validation;
     
     public function setUp()
    {
        $this->validation = new Validation();
    }
    
    public function tearDown()
    {
        unset($this->validation);
    }
 
    public function testValidation()
    {
        $this->assertTrue($this->validation->isSuccess());
        $this->assertFalse($this->validation->isError());
        $this->assertNull($this->validation->getErrorMessage());
    }
    
    public function testError()
    {
        /*
        Cause Error
        $this->assertFalse($this->validation->isSuccess());
        $this->assertTrue($this->validation->isError());
        //$this->assertNull($this->validation->getErrorMessage());
        */
        $this->validation->clearError();
        $this->assertTrue($this->validation->isSuccess());
        $this->assertFalse($this->validation->isError());
        $this->assertNull($this->validation->getErrorMessage());
    }
    
    public function testRequiredTrue()
    {
        $this->markTestIncomplete();
    }
}
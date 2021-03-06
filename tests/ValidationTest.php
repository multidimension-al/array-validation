<?php
/**
 *       __  ___      ____  _     ___                           _                    __
 *      /  |/  /_  __/ / /_(_)___/ (_)___ ___  ___  ____  _____(_)___  ____   ____ _/ /
 *     / /|_/ / / / / / __/ / __  / / __ `__ \/ _ \/ __ \/ ___/ / __ \/ __ \ / __ `/ /
 *    / /  / / /_/ / / /_/ / /_/ / / / / / / /  __/ / / (__  ) / /_/ / / / // /_/ / /
 *   /_/  /_/\__,_/_/\__/_/\__,_/_/_/ /_/ /_/\___/_/ /_/____/_/\____/_/ /_(_)__,_/_/
 *
 *  Array Validation Library
 *  Copyright (c) Multidimension.al (http://multidimension.al)
 *  Github : https://github.com/multidimension-al/array-validation
 *
 *  Licensed under The MIT License
 *  For full copyright and license information, please see the LICENSE file
 *  Redistributions of files must retain the above copyright notice.
 *
 *  @copyright  Copyright © 2017-2019 Multidimension.al (http://multidimension.al)
 *  @link       https://github.com/multidimension-al/array-validation Github
 *  @license    http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace Multidimensional\ArrayValidation\Test;

use Exception;
use Multidimensional\ArrayValidation\Validation;
use PHPUnit\Framework\TestCase;

class ValidationTest extends TestCase
{

    public function testEmptyValidation()
    {
        $this->assertTrue(Validation::validate([], []));
    }

    public function testFailedValidation()
    {
        try {
            $this->assertFalse(Validation::validate('', ''));
        } catch (Exception $e) {
            $this->assertEquals('Validation array not found.', $e->getMessage());
        }

        try {
            $this->assertFalse(Validation::validate([], ''));
        } catch (Exception $e) {
            $this->assertEquals('Validation rules array not found.', $e->getMessage());
        }

        try {
            $this->assertFalse(Validation::validate('', []));
        } catch (Exception $e) {
            $this->assertEquals('Validation array not found.', $e->getMessage());
        }
    }

    public function testRequiredTrue()
    {
        $rules = [
            'a' => ['type' => 'string', 'required' => true],
            'b' => ['type' => 'string']
        ];
        $array = [
            'a' => 'Hello',
            'b' => 'World'
        ];
        $this->assertTrue(Validation::validate($array, $rules));

        $array = [
            'b' => 'Goodbye'
        ];

        try {
            $this->assertFalse(Validation::validate($array, $rules));
        } catch (Exception $e) {
            $this->assertEquals('Required value not found for key: a.', $e->getMessage());
        }

        $rules['a']['required'] = 'true';

        try {
            $this->assertFalse(Validation::validate($array, $rules));
        } catch (Exception $e) {
            $this->assertEquals('Required value not found for key: a.', $e->getMessage());
        }
    }

    public function testValidateValues()
    {
        $rules = [
            'a' => ['type' => 'string', 'values' => ['a', 'b', 'c']]
        ];
        $array = [
            'a' => 'b'
        ];
        $this->assertTrue(Validation::validate($array, $rules));
    }

    public function testValidateValuesFailure()
    {
        $rules = [
            'a' => ['type' => 'string', 'values' => ['cat', 'dog']]
        ];
        $array = [
            'a' => 'kat'
        ];
        try {
            $this->assertFalse(Validation::validate($array, $rules));
        } catch (Exception $e) {
            $this->assertEquals('Invalid value "kat" for key: a. Did you mean "cat"?', $e->getMessage());
        }
    }

    public function testValidateValuesOnlyOption()
    {
        $rules = [
            'a' => ['type' => 'string', 'values' => 'b']
        ];
        $array = [
            'a' => 'b'
        ];
        $this->assertTrue(Validation::validate($array, $rules));
    }

    public function testInteger()
    {
        $rules = [
            'a' => ['type' => 'integer'],
            'b' => ['type' => 'integer']
        ];
        $array = [
            'a' => 1,
            'b' => 2
        ];
        $this->assertTrue(Validation::validate($array, $rules));
        $array = [
            'a' => 1,
            'b' => 'one'
        ];

        try {
            $this->assertFalse(Validation::validate($array, $rules));
        } catch (Exception $e) {
            $this->assertEquals('Invalid integer "one" for key: b.', $e->getMessage());
        }
    }

    public function testDecimal()
    {
        $rules = [
            'a' => ['type' => 'decimal'],
            'b' => ['type' => 'decimal']
        ];
        $array = [
            'a' => 1.0,
            'b' => 2.1
        ];
        $this->assertTrue(Validation::validate($array, $rules));
        $array = [
            'a' => 1.0,
            'b' => 'one point 2'
        ];

        try {
            $this->assertFalse(Validation::validate($array, $rules));
        } catch (Exception $e) {
            $this->assertEquals('Invalid decimal "one point 2" for key: b.', $e->getMessage());
        }
    }

    public function testRequiredDecimal()
    {
        $rules = [
            'a' => ['type' => 'decimal', 'required' => true]
        ];
        $array = [
            'a' => 0
        ];
        $this->assertTrue(Validation::validate($array, $rules));
    }

    public function testString()
    {
        $rules = [
            'a' => ['type' => 'string'],
            'b' => ['type' => 'string']
        ];
        $array = [
            'a' => 'Yes this is obviously',
            'b' => "a string"
        ];
        $this->assertTrue(Validation::validate($array, $rules));
        $array = [
            'a' => 1,
            'b' => 'one point 2'
        ];

        try {
            $this->assertFalse(Validation::validate($array, $rules));
        } catch (Exception $e) {
            $this->assertEquals('Invalid string "1" for key: a.', $e->getMessage());
        }
    }

    public function testBoolean()
    {
        $rules = [
            'a' => ['type' => 'boolean'],
            'b' => ['type' => 'boolean']
        ];
        $array = [
            'a' => true,
            'b' => false
        ];
        $this->assertTrue(Validation::validate($array, $rules));

        $array = [
            'a' => 'true',
            'b' => 'false'
        ];
        try {
            $this->assertFalse(Validation::validate($array, $rules));
        } catch (Exception $e) {
            $this->assertEquals('Invalid boolean "true" for key: a.', $e->getMessage());
        }

        $array = [
            'a' => 1,
            'b' => 'false'
        ];

        try {
            $this->assertFalse(Validation::validate($array, $rules));
        } catch (Exception $e) {
            $this->assertEquals('Invalid boolean "1" for key: a.', $e->getMessage());
        }
    }

    public function testInvaldType()
    {
        $rules = [
            'a' => ['type' => 'abcdef']
        ];
        $array = [
            'a' => 'This isn\'t real.'
        ];
        try {
            $this->assertFalse(Validation::validate($array, $rules));
        } catch (Exception $e) {
            $this->assertEquals('Invalid type "abcdef" for key: a.', $e->getMessage());
        }
    }

    public function testValidatePattern()
    {
        $rules = [
            'a' => ['type' => 'string', 'pattern' => '[A-Z]{2}'],
            'b' => ['type' => 'string', 'pattern' => 'ISO 8601']
        ];
        $array = [
            'a' => 'CA',
            'b' => '2014-01-22T14:30:51-06:00'
        ];
        $this->assertTrue(Validation::validate($array, $rules));

        $array = [
            'a' => 'CAT',
        ];

        try {
            $this->assertFalse(Validation::validate($array, $rules));
        } catch (Exception $e) {
            $this->assertEquals('Invalid value "CAT" does not match pattern "[A-Z]{2}" for key: a.', $e->getMessage());
        }

        $array = [
            'b' => '2014-01-22',
        ];

        try {
            $this->assertFalse(Validation::validate($array, $rules));
        } catch (Exception $e) {
            $this->assertEquals('Invalid value "2014-01-22" does not match ISO 8601 pattern for key: b.', $e->getMessage());
        }
    }

    public function testFieldNotInRules()
    {
        $rules = [
            'a' => ['type' => 'string']
        ];
        $array = [
            'a' => 'string',
            'b' => 'unexpected'
        ];

        try {
            $this->assertFalse(Validation::validate($array, $rules));
        } catch (Exception $e) {
            $this->assertEquals('Unexpected key "b" found in array.', $e->getMessage());
        }
    }

    public function testMultidimensionalValidation()
    {
        $rules = [
            'a' => ['type' => 'array',
                'fields' => [
                    'a' => ['type' => 'string'],
                    'b' => ['type' => 'string']
                ]
            ],
            'b' => ['type' => 'string']
        ];
        $array = [
            'a' => [
                'a' => 'string',
                'b' => 'test'
            ],
            'b' => 'b'
        ];
        $this->assertTrue(Validation::validate($array, $rules));

        $array = [
            'b' => [
                'a' => 'string',
                'b' => 'test'
            ]
        ];

        try {
            $this->assertFalse(Validation::validate($array, $rules));
        } catch (Exception $e) {
            $this->assertEquals("Unexpected array found for key: b.", $e->getMessage());
        }
    }

    public function testGroupValidation()
    {
        $rules2 = [
            'b' => ['type' => 'string']
        ];
        $rules = [
            'a' => [
                'type' => 'group',
                'fields' => $rules2
            ]
        ];
        $array = [
            'a' => [['b' => 'Hello']]
        ];
        $this->assertTrue(Validation::validate($array, $rules));
        $array = [
            'a' => [
                ['b' => 'Hello'],
                ['b' => 'World'],
            ]
        ];
        $this->assertTrue(Validation::validate($array, $rules));
    }

    public function testRequiredComplex()
    {
        $rules = [
            'a' => ['type' => 'string', 'required' => true],
            'b' => ['type' => 'string',
                'required' => [
                    'a' => 'banana'
                ]
            ],
            'c' => ['type' => 'string',
                'required' => [
                    [
                        'a' => 'banana',
                        'b' => 'orange'
                    ],
                    [
                        'a' => 'banana',
                        'b' => 'carrot'
                    ],
                    [
                        'a' => 'pickle'
                    ],
                    [
                        'b' => 'banana'
                    ]
                ]
            ],
        ];
        $array = [
            'a' => 'apple'
        ];
        $this->assertTrue(Validation::validate($array, $rules));

        $array = [
            'a' => 'banana',
            'b' => 'orange',
            'c' => 'other'
        ];
        $this->assertTrue(Validation::validate($array, $rules));

        $array = [
            'a' => 'banana',
            'c' => 'orange'
        ];

        try {
            $this->assertFalse(Validation::validate($array, $rules));
        } catch (Exception $e) {
            $this->assertEquals('Required value not found for key: b.', $e->getMessage());
        }

        $array = [
            'a' => 'banana',
            'b' => '',
            'c' => 'orange'
        ];

        try {
            $this->assertFalse(Validation::validate($array, $rules));
        } catch (Exception $e) {
            $this->assertEquals('Required value not found for key: b.', $e->getMessage());
        }

        $array = [
            'a' => 'banana',
            'b' => 'carrot'
        ];

        try {
            $this->assertFalse(Validation::validate($array, $rules));
        } catch (Exception $e) {
            $this->assertEquals('Required value not found for key: c.', $e->getMessage());
        }


        $array = [
            'a' => 'banana',
            'b' => 'carrot',
            'c' => ''
        ];

        try {
            $this->assertFalse(Validation::validate($array, $rules));
        } catch (Exception $e) {
            $this->assertEquals('Required value not found for key: c.', $e->getMessage());
        }

        $array = [
            'b' => 'banana'
        ];

        try {
            $this->assertFalse(Validation::validate($array, $rules));
        } catch (Exception $e) {
            $this->assertEquals('Required value not found for key: a.', $e->getMessage());
        }

        $array = [
            'a' => '',
            'b' => 'banana',
            'c' => ''
        ];

        try {
            $this->assertFalse(Validation::validate($array, $rules));
        } catch (Exception $e) {
            $this->assertEquals('Required value not found for key: a.', $e->getMessage());
        }


        $array = [
            'a' => 'carrot',
            'b' => 'banana'
        ];

        try {
            $this->assertFalse(Validation::validate($array, $rules));
        } catch (Exception $e) {
            $this->assertEquals('Required value not found for key: c.', $e->getMessage());
        }
    }

    public function testRequiredNull()
    {
        $rules = [
            'a' => ['type' => 'string',
                'required' => [
                    'b' => 'null'
                ]
            ],
            'b' => ['type' => 'string'],
            'c' => ['type' => 'string'],
        ];
        $array = [
            'b' => 'not a null value',
            'c' => 'no one cares about c'
        ];
        $this->assertTrue(Validation::validate($array, $rules));

        $array = [
            'c' => 'c is lonely'
        ];

        try {
            $this->assertFalse(Validation::validate($array, $rules));
        } catch (Exception $e) {
            $this->assertEquals('Required value not found for key: a.', $e->getMessage());
        }

        $array = [];

        try {
            $this->assertFalse(Validation::validate($array, $rules));
        } catch (Exception $e) {
            $this->assertEquals('Required value not found for key: a.', $e->getMessage());
        }

        $rules['a']['required']['b'] = null;

        try {
            $this->assertFalse(Validation::validate($array, $rules));
        } catch (Exception $e) {
            $this->assertEquals('Required value not found for key: a.', $e->getMessage());
        }
    }

    public function testNullValue()
    {
        $rules = [
            'a' => ['type' => 'string']
        ];
        $array = [
            'a' => null
        ];
        $this->assertTrue(Validation::validate($array, $rules));

        $array['a'] = 'null';
        $this->assertTrue(Validation::validate($array, $rules));
    }

    public function testInvalidRequiredValue()
    {
        $rules = [
            'a' => [
                'type' => 'string',
                'required' => 'banana'
            ]
        ];
        $array = [
            'a' => null
        ];

        try {
            $this->assertFalse(Validation::validate($array, $rules));
        } catch (Exception $e) {
            $this->assertEquals('Required value not found for key: a.', $e->getMessage());
        }
    }

    public function testRequiredWhenNull()
    {
        $rules = [
            'a' =>
                ['type' => 'string',
                    'required' => [
                        'b' => null
                    ]
                ],
            'b' => ['type' => 'string'],
            'c' => ['type' => 'string']
        ];
        $array = ['c' => 'Hi'];

        try {
            $this->assertFalse(Validation::validate($array, $rules));
        } catch (Exception $e) {
            $this->assertEquals('Required value not found for key: a.', $e->getMessage());
        }
    }

    public function testRequiredWhenEmpty()
    {
        $rules = [
            'a' =>
                ['type' => 'string',
                    'required' => [
                        'b' => ''
                    ]
                ],
            'b' => ['type' => 'string'],
            'c' => ['type' => 'string']
        ];
        $array = ['c' => 'Hi'];

        try {
            $this->assertFalse(Validation::validate($array, $rules));
        } catch (Exception $e) {
            $this->assertEquals('Required value not found for key: a.', $e->getMessage());
        }
    }

    public function testUnexpectedArray()
    {
        $rules = [
            'a' => [
                'type' => 'string'
            ]
        ];
        $array = [
            'a' => [
                'b' => 'c'
            ]
        ];
        try {
            $this->assertFalse(Validation::validate($array, $rules));
        } catch (Exception $e) {
            $this->assertEquals('Unexpected array found for key: a.', $e->getMessage());
        }
    }

    public function testMultiDimensionalArray()
    {
        $rules = [
            'RateV4Response' => [
                'type' => 'array',
                'fields' => [
                    'Package' => [
                        'type' => 'group',
                        'fields' => [
                            '@ID' => [
                                'type' => 'string',
                                'required' => true
                            ],
                            'ZipOrigination' => [
                                'type' => 'string',
                                'required' => true,
                                'pattern' => '\d{5}'
                            ],
                            'ZipDestination' => [
                                'type' => 'string',
                                'required' => true,
                                'pattern' => '\d{5}'
                            ],
                            'Pounds' => [
                                'type' => 'decimal',
                                'required' => true,
                            ],
                            'Ounces' => [
                                'type' => 'decimal',
                                'required' => true,
                            ],
                            'FirstClassMailType' => [
                                'type' => 'string'
                            ],
                            'Container' => [
                                'type' => 'string',
                            ],
                            'Size' => [
                                'type' => 'string',
                                'required' => true,
                                'values' => [
                                    'REGULAR',
                                    'LARGE'
                                ]
                            ],
                            'Width' => [
                                'type' => 'decimal'
                            ],
                            'Length' => [
                                'type' => 'decimal'
                            ],
                            'Height' => [
                                'type' => 'decimal'
                            ],
                            'Girth' => [
                                'type' => 'decimal'
                            ],
                            'Machinable' => [
                                'type' => 'boolean'
                            ],
                            'Zone' => [
                                'type' => 'string'
                            ],
                            'Postage' => [
                                'type' => 'group',
                                'required' => true,
                                'fields' => [
                                    '@CLASSID' => [
                                        'type' => 'integer'
                                    ],
                                    'MailService' => [
                                        'type' => 'string'
                                    ],
                                    'Rate' => [
                                        'type' => 'decimal'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $array = [
            'RateV4Response' => [
                'Package' => [
                    '@ID' => '123',
                    'ZipOrigination' => '20500',
                    'ZipDestination' => '90210',
                    'Pounds' => 0.0,
                    'Ounces' => 32.0,
                    'Size' => 'REGULAR',
                    'Machinable' => true,
                    'Zone' => '8',
                    'Postage' => [
                        0 => [
                            '@CLASSID' => 1,
                            'MailService' => 'Priority Mail 2-Day<sup>™</sup>',
                            'Rate' => 12.75
                        ],
                        1 => [
                            '@CLASSID' => 22,
                            'MailService' => 'Priority Mail 2-Day<sup>™</sup> Large Flat Rate Box',
                            'Rate' => 18.85
                        ],
                        2 => [
                            '@CLASSID' => 17,
                            'MailService' => 'Priority Mail 2-Day<sup>™</sup> Medium Flat Rate Box',
                            'Rate' => 13.60
                        ],
                        3 => [
                            '@CLASSID' => 28,
                            'MailService' => 'Priority Mail 2-Day<sup>™</sup> Small Flat Rate Box',
                            'Rate' => 7.15
                        ]
                    ]
                ]
            ]
        ];
        $this->assertTrue(Validation::validate($array, $rules));
    }

    public function testMultiMultiMultidimensionalArray()
    {
        $rules = [
            'RateV4Response' => [
                'type' => 'array',
                'fields' => [
                    'Package' => [
                        'type' => 'group',
                        'fields' => [
                            '@ID' => [
                                'type' => 'string',
                                'required' => true
                            ],
                            'ZipOrigination' => [
                                'type' => 'string',
                                'required' => true,
                                'pattern' => '\d{5}'
                            ],
                            'ZipDestination' => [
                                'type' => 'string',
                                'required' => true,
                                'pattern' => '\d{5}'
                            ],
                            'Pounds' => [
                                'type' => 'decimal',
                                'required' => true,
                            ],
                            'Ounces' => [
                                'type' => 'decimal',
                                'required' => true,
                            ],
                            'FirstClassMailType' => [
                                'type' => 'string'
                            ],
                            'Container' => [
                                'type' => 'string',
                            ],
                            'Size' => [
                                'type' => 'string',
                                'required' => true,
                                'values' => [
                                    'REGULAR',
                                    'LARGE'
                                ]
                            ],
                            'Width' => [
                                'type' => 'decimal'
                            ],
                            'Length' => [
                                'type' => 'decimal'
                            ],
                            'Height' => [
                                'type' => 'decimal'
                            ],
                            'Girth' => [
                                'type' => 'decimal'
                            ],
                            'Machinable' => [
                                'type' => 'boolean'
                            ],
                            'Zone' => [
                                'type' => 'string'
                            ],
                            'Postage' => [
                                'type' => 'group',
                                'required' => true,
                                'fields' => [
                                    '@CLASSID' => [
                                        'type' => 'integer'
                                    ],
                                    'MailService' => [
                                        'type' => 'string'
                                    ],
                                    'Rate' => [
                                        'type' => 'decimal'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $array = [
            'RateV4Response' => [
                'Package' => [
                    0 => [
                        '@ID' => '123',
                        'ZipOrigination' => '20500',
                        'ZipDestination' => '90210',
                        'Pounds' => 0.0,
                        'Ounces' => 32.0,
                        'Size' => 'REGULAR',
                        'Machinable' => true,
                        'Zone' => '8',
                        'Postage' => [
                            0 => [
                                '@CLASSID' => 1,
                                'MailService' => 'Priority Mail 2-Day<sup>™</sup>',
                                'Rate' => 12.75
                            ],
                            1 => [
                                '@CLASSID' => 22,
                                'MailService' => 'Priority Mail 2-Day<sup>™</sup> Large Flat Rate Box',
                                'Rate' => 18.85
                            ],
                            2 => [
                                '@CLASSID' => 17,
                                'MailService' => 'Priority Mail 2-Day<sup>™</sup> Medium Flat Rate Box',
                                'Rate' => 13.60
                            ],
                            3 => [
                                '@CLASSID' => 28,
                                'MailService' => 'Priority Mail 2-Day<sup>™</sup> Small Flat Rate Box',
                                'Rate' => 7.15
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $this->assertTrue(Validation::validate($array, $rules));
    }
}

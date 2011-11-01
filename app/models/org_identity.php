<?php
  /*
   * COmanage Gears Organizational Person Model
   *
   * Version: $Revision$
   * Date: $Date$
   *
   * Copyright (C) 2010-2011 University Corporation for Advanced Internet Development, Inc.
   * 
   * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with
   * the License. You may obtain a copy of the License at
   * 
   * http://www.apache.org/licenses/LICENSE-2.0
   * 
   * Unless required by applicable law or agreed to in writing, software distributed under
   * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
   * KIND, either express or implied. See the License for the specific language governing
   * permissions and limitations under the License.
   *
   */

  class OrgIdentity extends AppModel {
    // Define class name for cake
    var $name = "OrgIdentity";
    
    // Association rules from this model to other models
    var $hasOne = array("Name" =>                     // A person can have one (preferred) name per Org
                        array('dependent' => true));  // This could change if Name became an MVPA
    
    var $hasMany = array("Address" =>                 // A person can have one or more address
                         array('dependent' => true),
                         "CoOrgIdentityLink" =>       // An Org Identity can be attached to one or more CO Person
                         array('dependent' => false), // Current design requires all links to be dropped manually
                         "CoPetition" =>              // A person can have various roles for a petition
                         array('dependent' => true,
                               'foreignKey' => 'enrollee_org_identity_id'),
                         "EmailAddress" =>            // A person can have one or more email address
                         array('dependent' => true),
                         "Identifier" =>              // A person can have many identifiers within an organization
                         array('dependent' => true),
                         "TelephoneNumber" =>         // A person can have one or more telephone numbers
                         array('dependent' => true));

    var $belongsTo = array("Organization");       // A person may belong to an organization (if pre-defined)
    
    // Default display field for cake generated views
    var $displayField = "Name.family";
    
    // Default ordering for find operations
    var $order = array("Name.family", "Name.given");
    
    // Validation rules for table elements
    var $validate = array(
      'edu_person_affiliation' => array(
        'rule' => array('inList', array('faculty', 'student', 'staff', 'alum', 'member', 'affiliate', 'employee', 'library-walk-in')),
        'required' => true,
        'message' => 'A valid affiliation must be selected'
      )
      // 'o'
      // 'ou'
      // 'title'
    );
  }
?>
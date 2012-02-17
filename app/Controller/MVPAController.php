<?php
/**
 * COmanage Registry Multi-Value Person Attribute (MVPA) Controller
 *
 * Copyright (C) 2010-12 University Corporation for Advanced Internet Development, Inc.
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
 * @copyright     Copyright (C) 2010-12 University Corporation for Advanced Internet Development, Inc.
 * @link          http://www.internet2.edu/comanage COmanage Project
 * @package       registry
 * @since         COmanage Registry v0.1
 * @license       Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 * @version       $Id$
 */

App::uses("StandardController", "Controller");

class MVPAController extends StandardController {
  // MVPAs require a Person ID (CO or Org)
  public $requires_person = true;
  
  // We need to increase recursion because CoPerson/OrgPerson doesn't
  // define name, and we need it to render the columns.
  public $edit_recursion = 2;
  public $view_recursion = 2;
  
  /**
   * Callback before other controller methods are invoked or views are rendered.
   * - postcondition: requires_co possibly set
   *
   * @since  COmanage Registry v0.4
   */
  
  function beforeFilter() {
    // MVPA controllers may or may not require a CO, depending on how
    // the CMP Enrollment Configuration is set up. Check and adjust before
    // beforeFilter is called.
    
    // Get a pointer to our model
    $req = $this->modelClass;
    $model = $this->$req;
    
    // For HTML views, require CO for proper rendering.
    
    $this->loadModel('CmpEnrollmentConfiguration');
    $pool = $this->CmpEnrollmentConfiguration->orgIdentitiesPooled();
    
    if(!$pool) {
      $this->requires_co = true;
      
      // Associate the CO model
      $this->loadModel('Co');
    }
    
    // The views will also need this
    $this->set('pool_org_identities', $pool);
    
    parent::beforeFilter();
    
    if($this->restful && $this->requires_co) {
      // For REST views, the CO isn't required by the data model since it's
      // implied by the person ID. We can't unload the model (though we could
      // figure out associations and $models->unbindModel), but we don't really
      // need to as long as we flag requires_co=false. We do this after beforeFilter()
      // because $this->restful gets calculated there.
      
      $this->requires_co = false;
    }
  }
}

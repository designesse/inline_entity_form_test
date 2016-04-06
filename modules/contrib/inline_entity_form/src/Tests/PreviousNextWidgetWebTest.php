<?php

namespace Drupal\inline_entity_form\Tests;

use Drupal\node\Entity\Node;

/**
 * IEF complex field widget tests.
 *
 * @group inline_entity_form
 */
class PreviousNextWidgetWebTest extends InlineEntityFormTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'inline_entity_form_test',
    'field',
    'field_ui',
  ];

  /**
   * URL to add new content.
   *
   * @var string
   */
  protected $formContentAddUrl;

  /**
   * Entity form display storage.
   *
   * @var \Drupal\Core\Config\Entity\ConfigEntityStorageInterface
   */
  protected $entityFormDisplayStorage;

  /**
   * Field config storage.
   *
   * @var \Drupal\Core\Config\Entity\ConfigEntityStorageInterface
   */
  protected $fieldConfigStorage;

  /**
   * Prepares environment for
   */
  protected function setUp() {
    parent::setUp();

    $this->user = $this->createUser([
      'create ief_reference_type content',
      'edit any ief_reference_type content',
      'delete any ief_reference_type content',
      'create ief_test_complex content',
      'edit any ief_test_complex content',
      'delete any ief_test_complex content',
      'edit any ief_test_nested1 content',
      'edit any ief_test_nested2 content',
      'edit any ief_test_nested3 content',
      'view own unpublished content',
      'administer content types',
    ]);
    $this->drupalLogin($this->user);

    $this->formContentAddUrl = 'node/add/ief_test_complex';
    $this->entityFormDisplayStorage = $this->container->get('entity_type.manager')->getStorage('entity_form_display');
    $this->fieldConfigStorage = $this->container->get('entity_type.manager')->getStorage('field_config');
  }

  /**
   * Tests if previous button shows and works properly. and save entity
   */
  public function testPreviousButton() {
    // Allow addition of existing nodes.
    $this->setAllowExisting(TRUE);

    // Create three ief_reference_type entities.
    $referenceNodes = $this->createReferenceContent(3);
    $this->drupalCreateNode([
      'type' => 'ief_test_complex',
      'title' => 'Some title',
      'multi' => array_values($referenceNodes),
    ]);
    /** @var \Drupal\node\NodeInterface $node */
    $parent_node = $this->drupalGetNodeByTitle('Some title');

    // Edit the second entity.
    $this->drupalGet('node/'. $parent_node->id() .'/edit');
    $cell = $this->xpath('//table[@id="ief-entity-table-edit-multi-entities"]/tbody/tr[@class="ief-row-entity draggable even"]/td[@class="inline-entity-form-node-label"]');
    $title = (string) $cell[0];

    $this->drupalPostAjaxForm(NULL, [], $this->getButtonName('//input[@type="submit" and @id="edit-multi-entities-1-actions-ief-entity-edit"]'));
    $this->assertResponse(200, 'Opening inline edit form was successful.');

    $edit = [
      'multi[form][inline_entity_form][entities][1][form][first_name][0][value]' => 'John',
      'multi[form][inline_entity_form][entities][1][form][last_name][0][value]' => 'Doe',
    ];
    $this->drupalPostAjaxForm(NULL, $edit, $this->getButtonName('//input[@type="submit" and @data-drupal-selector="edit-multi-form-inline-entity-form-entities-1-form-actions-ief-edit-save"]'));
    $this->assertResponse(200, 'Saving inline edit form was successful.');

    // Save the ief_test_complex node.
    $this->drupalPostForm(NULL, [], t('Save'));
    $this->assertResponse(200, 'Saving parent entity was successful.');

    // Checks values of changed entities.
    $node = $this->drupalGetNodeByTitle($title, TRUE);
    $this->assertTrue($node->first_name->value == 'John', 'First name in reference node changed to John');
    $this->assertTrue($node->last_name->value == 'Doe', 'Last name in reference node changed to Doe');
  }

  /**
   * Tests if next button shows and works properly.
   */
  public function testNextButton() {

  }

  /**
   * Tests if form behaves correctly when field is empty.
   */
  public function testEmptyFieldIEF() {
//    // Don't allow addition of existing nodes.
//    $this->setAllowExisting(FALSE);
//    $this->drupalGet($this->formContentAddUrl);
//
//    $this->assertFieldByName('multi[form][inline_entity_form][title][0][value]', NULL, 'Title field on inline form exists.');
//    $this->assertFieldByName('multi[form][inline_entity_form][first_name][0][value]', NULL, 'First name field on inline form exists.');
//    $this->assertFieldByName('multi[form][inline_entity_form][last_name][0][value]', NULL, 'Last name field on inline form exists.');
//    $this->assertFieldByXpath('//input[@type="submit" and @value="Create node"]', NULL, 'Found "Create node" submit button');
//
//    // Allow addition of existing nodes.
//    $this->setAllowExisting(TRUE);
//    $this->drupalGet($this->formContentAddUrl);
//
//    $this->assertNoFieldByName('multi[form][inline_entity_form][title][0][value]', NULL, 'Title field does not appear.');
//    $this->assertNoFieldByName('multi[form][inline_entity_form][first_name][0][value]', NULL, 'First name field does not appear.');
//    $this->assertNoFieldByName('multi[form][inline_entity_form][last_name][0][value]', NULL, 'Last name field does not appear.');
//    $this->assertFieldByXpath('//input[@type="submit" and @value="Add new node"]', NULL, 'Found "Add new node" submit button');
//    $this->assertFieldByXpath('//input[@type="submit" and @value="Add existing node"]', NULL, 'Found "Add existing node" submit button');
//
//    // Now submit 'Add new node' button.
//    $this->drupalPostAjaxForm(NULL, [], $this->getButtonName('//input[@type="submit" and @value="Add new node" and @data-drupal-selector="edit-multi-actions-ief-add"]'));
//
//    $this->assertFieldByName('multi[form][inline_entity_form][title][0][value]', NULL, 'Title field on inline form exists.');
//    $this->assertFieldByName('multi[form][inline_entity_form][first_name][0][value]', NULL, 'First name field on inline form exists.');
//    $this->assertFieldByName('multi[form][inline_entity_form][last_name][0][value]', NULL, 'Second name field on inline form exists.');
//    $this->assertFieldByXpath('//input[@type="submit" and @value="Create node"]', NULL, 'Found "Create node" submit button');
//    $this->assertFieldByXpath('//input[@type="submit" and @value="Cancel"]', NULL, 'Found "Cancel" submit button');
//
//    // Now submit 'Add Existing node' button.
//    $this->drupalGet($this->formContentAddUrl);
//    $this->drupalPostAjaxForm(NULL, [], $this->getButtonName('//input[@type="submit" and @value="Add existing node" and @data-drupal-selector="edit-multi-actions-ief-add-existing"]'));
//
//    $this->assertFieldByName('multi[form][entity_id]', NULL, 'Existing entity reference autocomplete field found.');
//    $this->assertFieldByXpath('//input[@type="submit" and @value="Add node"]', NULL, 'Found "Add node" submit button');
//    $this->assertFieldByXpath('//input[@type="submit" and @value="Cancel"]', NULL, 'Found "Cancel" submit button');
  }


//  /**
//   * Tests creation of entities.
//   */
//  public function testEntityCreation() {
    /*
    // Allow addition of existing nodes.
    $this->setAllowExisting(TRUE);
    $this->drupalGet($this->formContentAddUrl);

    $this->drupalPostAjaxForm(NULL, [], $this->getButtonName('//input[@type="submit" and @value="Add new node" and @data-drupal-selector="edit-multi-actions-ief-add"]'));
    $this->assertResponse(200, 'Opening new inline form was successful.');

    $this->drupalPostAjaxForm(NULL, [], $this->getButtonName('//input[@type="submit" and @value="Create node" and @data-drupal-selector="edit-multi-form-inline-entity-form-actions-ief-add-save"]'));
    $this->assertResponse(200, 'Submitting empty form was successful.');
    $this->assertText('First name field is required.', 'Validation failed for empty "First name" field.');
    $this->assertText('Last name field is required.', 'Validation failed for empty "Last name" field.');
    $this->assertText('Title field is required.', 'Validation failed for empty "Title" field.');

    // Create ief_reference_type node in IEF.
    $this->drupalGet($this->formContentAddUrl);
    $this->drupalPostAjaxForm(NULL, [], $this->getButtonName('//input[@type="submit" and @value="Add new node" and @data-drupal-selector="edit-multi-actions-ief-add"]'));
    $this->assertResponse(200, 'Opening new inline form was successful.');

    $edit = [
      'multi[form][inline_entity_form][title][0][value]' => 'Some reference',
      'multi[form][inline_entity_form][first_name][0][value]' => 'John',
      'multi[form][inline_entity_form][last_name][0][value]' => 'Doe',
    ];
    $this->drupalPostAjaxForm(NULL, $edit, $this->getButtonName('//input[@type="submit" and @value="Create node" and @data-drupal-selector="edit-multi-form-inline-entity-form-actions-ief-add-save"]'));
    $this->assertResponse(200, 'Creating node via inline form was successful.');

    // Tests if correct fields appear in the table.
    $this->assertTrue((bool) $this->xpath('//td[@class="inline-entity-form-node-label" and contains(.,"Some reference")]'), 'Node title field appears in the table');
    $this->assertTrue((bool) $this->xpath('//td[@class="inline-entity-form-node-status" and ./div[contains(.,"Published")]]'), 'Node status field appears in the table');

    // Tests if edit and remove buttons appear.
    $this->assertTrue((bool) $this->xpath('//input[@type="submit" and @value="Edit"]'), 'Edit button appears in the table.');
    $this->assertTrue((bool) $this->xpath('//input[@type="submit" and @value="Remove"]'), 'Remove button appears in the table.');

    // Test edit functionality.
    $this->drupalPostAjaxForm(NULL, [], $this->getButtonName('//input[@type="submit" and @value="Edit"]'));
    $edit = [
      'multi[form][inline_entity_form][entities][0][form][title][0][value]' => 'Some changed reference',
    ];
    $this->drupalPostAjaxForm(NULL, $edit, $this->getButtonName('//input[@type="submit" and @value="Update node"]'));
    $this->assertTrue((bool) $this->xpath('//td[@class="inline-entity-form-node-label" and contains(.,"Some changed reference")]'), 'Node title field appears in the table');
    $this->assertTrue((bool) $this->xpath('//td[@class="inline-entity-form-node-status" and ./div[contains(.,"Published")]]'), 'Node status field appears in the table');

    // Make sure unrelated AJAX submit doesn't save the referenced entity.
    $this->drupalPostAjaxForm(NULL, [], $this->getButtonName('//input[@type="submit" and @value="Upload"]'));
    $node = $this->drupalGetNodeByTitle('Some changed reference');
    $this->assertFalse($node, 'Referenced node was not saved during unrelated AJAX submit.');

    // Create ief_test_complex node.
    $edit = ['title[0][value]' => 'Some title'];
    $this->drupalPostForm(NULL, $edit, t('Save'));
    $this->assertResponse(200, 'Saving parent entity was successful.');

    // Checks values of created entities.
    $node = $this->drupalGetNodeByTitle('Some changed reference');
    $this->assertTrue($node, 'Created ief_reference_type node ' . $node->label());
    $this->assertTrue($node->get('first_name')->value == 'John', 'First name in reference node set to John');
    $this->assertTrue($node->get('last_name')->value == 'Doe', 'Last name in reference node set to Doe');

    $parent_node = $this->drupalGetNodeByTitle('Some title');
    $this->assertTrue($parent_node, 'Created ief_test_complex node ' . $parent_node->label());
    $this->assertTrue($parent_node->multi->target_id == $node->id(), 'Refererence node id set to ' . $node->id());
    */
//  }

  /**
   * Sets allow_existing IEF setting.
   *
   * @param bool $flag
   *   "allow_existing" flag to be set.
   */
  protected function setAllowExisting($flag) {
    /** @var \Drupal\Core\Entity\Display\EntityFormDisplayInterface $display */
    $display = $this->entityFormDisplayStorage->load('node.ief_test_complex.default');
    $component = $display->getComponent('multi');
    $component['settings']['allow_existing'] = $flag;
    $display->setComponent('multi', $component)->save();
  }

  /**
   * Creates ief_reference_type nodes which shall serve as reference nodes.
   *
   * @param int $numNodes
   *   The number of nodes to create
   * @return array
   *   Array of created node ids keyed by labels.
   */
  protected function createReferenceContent($numNodes = 3) {
    $retval = [];
    for ($i = 1; $i <= $numNodes; $i++) {
      $this->drupalCreateNode([
        'type' => 'ief_reference_type',
        'title' => 'Some reference ' . $i,
        'first_name' => 'First Name ' . $i,
        'last_name' => 'Last Name ' . $i,
      ]);
      $node = $this->drupalGetNodeByTitle('Some reference ' . $i);
      $this->assertTrue($node, 'Created ief_reference_type node "' . $node->label() . '"');
      $retval[$node->label()] = $node->id();
    }
    return $retval;
  }

}

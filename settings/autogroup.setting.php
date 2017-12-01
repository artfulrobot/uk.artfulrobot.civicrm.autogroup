<?php
/**
 * @file
 * Settings for this extensions.
 *
 * Nb. this file is only sourced by
 *     cv api system.flush
 *
 */
return  [
  'autogroup_groups_to_copy' => [
    'group_name'  => 'Groups to copy',
    'group'       => 'autogroup',
    'name'        => 'autogroup_groups_to_copy',
    'type'        => 'Array',
    'default'     => '',
    'add'         => '4.7',
    'is_domain'   => 1,
    'is_contact'  => 0,
    'description' => 'Groups belonging to the signed-in user that will be copied to created contacts',
    'help_text'   => '',
    // for forms
    'quick_form_type' => 'Element', // Without this it will not appear on the settings form.
    'html_type' => 'select',
    'title' => 'Groups to copy',
  ],
];

<?php

/**
 * Devices summary view.
 *
 * @category   apps
 * @package    storage
 * @subpackage views
 * @author     ClearFoundation ericampire.top@gmail.com
 * @copyright  2018 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/storage/
 */

///////////////////////////////////////////////////////////////////////////////
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.  
//  
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// Load dependencies
///////////////////////////////////////////////////////////////////////////////

$this->lang->load('base');
$this->lang->load('storage');

///////////////////////////////////////////////////////////////////////////////
// Headers
///////////////////////////////////////////////////////////////////////////////

$headers = array(
    lang('storage_device'),
    lang('storage_model'),
    lang('storage_size'),
    lang('storage_in_use'),
    lang('storage_storage')
);

///////////////////////////////////////////////////////////////////////////////
// Anchors 
///////////////////////////////////////////////////////////////////////////////

$anchors = array();

///////////////////////////////////////////////////////////////////////////////
// Items
///////////////////////////////////////////////////////////////////////////////

foreach ($devices as $device => $details) {
    $device_encoded = strtr(base64_encode($device),  '+/=', '-_.');

    // Skip removable drives
    // if ($details['removable'])
    //     continue;

    // TODO: discuss icon strategy
    $in_use_icon = ($details['in_use']) ? '<span class="theme-icon-ok">&nbsp;</span>' : '';
    $is_store = ($details['is_store']) ? '<span class="theme-icon-ok">&nbsp;</span>' : '';
    $identifier = $details['identifier'];

    $item['title'] = $device;
    $item['action'] = '';
    
    $buttons = NULL;
    if ($details['removable'] && $details['is_mounted']) {
        $buttons = button_set(array(
            anchor_custom('/app/storage/devices/view/' . $device_encoded, lang('base_view_details')),
            anchor_custom('/app/storage/devices/unmount_device/' . $device_encoded . $id, "Demonter")
        ));
        
    } elseif ($details['removable'] && !($details['is_mounted'])) {
        $buttons = button_set(array(
            anchor_custom('/app/storage/devices/view/' . $device_encoded, lang('base_view_details')),
            anchor_custom('/app/storage/devices/mount_device/' . $device_encoded . $id, "Monter")
        ));
        
    } else {
        $buttons = button_set(array(anchor_custom('/app/storage/devices/view/' . $device_encoded, lang('base_view_details'))));
    }
    
    $item['anchors'] = $buttons;
    
    $item['details'] = array(
        $device,
        $identifier,
        $details['size'] . ' ' . $details['size_units'],
        $in_use_icon,
        $is_store,
    );

    $items[] = $item;
}

sort($items);

// FIXME: Wizard
// in_use + is_store -> Storage is good to go!
// !in_use + !is_store -> Jump to create store view?
// in_use + !is_store -> No storage devices found, use directory on root partition?
// !in_use + is_store -> Storage not mounted ! 

///////////////////////////////////////////////////////////////////////////////
// Summary table
///////////////////////////////////////////////////////////////////////////////

$options = array(
    'id' => 'storage_summary',
    'responsive' => array(1 => 'none')
);
echo summary_table(
    lang('storage_devices'),
    $anchors,
    $headers,
    $items,
    $options
);

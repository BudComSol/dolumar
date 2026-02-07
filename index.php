<?php
/**
 *  Dolumar, browser based strategy game
 *  Copyright (C) 2009 Thijs Van der Schaeghe
 *  CatLab Interactive bvba, Gent, Belgium
 *  http://www.catlab.eu/
 *  http://www.dolumar.com/
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License along
 *  with this program; if not, write to the Free Software Foundation, Inc.,
 *  51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

/**
 * Root-level index.php for easier local development
 * 
 * This file allows the game to be accessed directly from the root directory
 * without needing to configure a web server to point to the public/ directory.
 * 
 * For production deployments, configure your web server to serve from the
 * public/ directory instead.
 */

// Change to public directory for proper context
$publicDir = __DIR__ . '/public';
if (!is_dir($publicDir)) {
    die('ERROR: The public/ directory was not found. Please ensure the game is properly installed.');
}

if (!chdir($publicDir)) {
    die('ERROR: Failed to change to the public/ directory. Please check directory permissions.');
}

// Include the actual index.php from the public directory
// Using relative path since we've already changed to the public directory
require 'index.php';

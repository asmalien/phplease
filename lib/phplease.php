<?php

namespace PHPlease;

class PHPlease {
	# PHPleaseAuth instance
	private $_client;

	# Constructor
	public function __construct(PHPleaseAuth $auth) {
		$this->_client = $auth;
	}

	# Returns information about a single release, specified by the complete dirname or an API release id.
    public function info($id) {
        return $this->_client->unsigned('release/info', (strpos($id, '.') ? ['dirname' => $id] : ['id' => $id]));
    }

    # Returns the latest releases. Also allows to browse the archive by month.
    public function latest($archive = null, $per_page = 25, $page = 1, $filter = 0) {
        return $this->_client->unsigned('release/latest', compact('archive', 'per_page', 'page', 'filter'));
    }

    # Returns scene releases from the given category.
    public function browse_category($ext_info_type, $category_name = 'TOPMOVIE') {
        return $this->_client->unsigned('release/browse_category', compact('ext_info_type', 'category_name'));
    }

    # Returns all releases associated with a given ext info.
    public function product_releases($id, $per_page = 25, $page = 1) {
        return $this->_client->unsigned('release/ext_info', compact('id', 'per_page', 'page'));
    }

    # Returns a list of public, predefined release filters.
    public function filters() {
        return $this->_client->unsigned('release/filters');
    }

    # Returns a list upcoming movies and their releases.
    public function upcoming() {
        return $this->_client->unsigned('calendar/upcoming');
    }

    # Returns information about a product info entry defined by the id parameter.
    public function product($id) {
        return $this->_client->unsigned('ext_info/info', (strpos($id, '.') ? ['dirname' => $id] : ['id' => $id]));
    }

    # Browse P2P/non-scene releases.
    public function p2p_releases($ext_info_id = null, $category_id = null, $group_id = null, $per_page = 25, $page = 1) {
        return $this->_client->unsigned('p2p/releases', compact('per_page', 'page', 'category_id', 'group_id', 'ext_info_id'));
    }

    # Returns a list of available P2P release categories and their IDs. You can use the category ID in p2p/releases.
    public function p2p_categories() {
        return $this->_client->unsigned('p2p/categories');
    }

    # Returns information about a single release, specified by the complete dirname or an API release id.
    public function p2p_release($dirname) {
        return $this->_client->unsigned('p2p/rls_info', compact('dirname'));
    }

    # Returns information about the currently active user.
    public function authd_user() {
        return $this->_client->signed('user/get_authd_user');
    }

    # Shows how many calls the user (if an OAuth session is present) or the IP address (otherwise) has left before none will be answered.
    public function rate_limit_status() {
        return $this->_client->signed('user/rate_limit_status');
    }

    # Returns a list of all the current user's favorite lists.
    public function favs_lists() {
        return $this->_client->signed('favs/lists');
    }

    # Returns a favorite list's entries.
    public function favs_list_entries($id, $get_releases = 0) {
        $lists = $this->favs_lists();
        foreach($lists as $list) {
            if($list['id'] === $id) {
                return $this->_client->signed('favs/list_entries', compact('id', 'get_releases'));
            }
        }

        return false;
    }
}

<?php
class User_model extends CI_Model {
    public function check_login($user, $pass) {
        // In a real app, use password_verify(). For now, we match plain text.
        $this->db->where('username', $user);
        $this->db->where('password', $pass);
        $query = $this->db->get('users');
        
        return $query->row(); 
    }
}
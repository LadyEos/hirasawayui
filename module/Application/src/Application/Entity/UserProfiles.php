<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class UserProfiles
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=30, nullable=TRUE)
     */
    protected $first_name;

    /**
     * @ORM\Column(type="string", length=30, nullable=TRUE)
     */
    protected $last_name;

    /**
     * @ORM\Column(type="string", nullable=TRUE)
     */
    protected $birthdate;

    /**
     * @ORM\Column(type="string", length=300, nullable=TRUE)
     */
    protected $profile_picture_url;

    /**
     * @ORM\Column(type="string", length=300, nullable=TRUE)
     */
    protected $biography;

    /**
     * @ORM\Column(type="string", length=300, nullable=TRUE)
     */
    protected $facebook_link;

    /**
     * @ORM\Column(type="string", length=50, nullable=TRUE)
     */
    protected $twitter_link;

    /**
     * @ORM\Column(type="string", length=100, nullable=TRUE)
     */
    protected $webpage;

    /**
     * @ORM\ManyToOne(targetEntity="Countries", inversedBy="profiles")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     */
    protected $countries;

    /**
     * @ORM\OneToOne(targetEntity="Users", mappedBy="user_profile")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=128, nullable=TRUE)
     */
    protected $displayname;
    
    /**
     * @ORM\Column(type="string", length=1, nullable=TRUE)
     */
    protected $gender;
    
    
    /**
     * @ORM\Column(type="string", length=128, nullable=TRUE)
     */
    protected $gravatar_email;
    
    // getters/setters
    public function getId()
    {
        return $this->id;
    }

    public function getFirst_name()
    {
        return $this->first_name;
    }

    public function setFirst_name($first_name)
    {
        $this->first_name = $first_name;
    }

    public function getLast_name()
    {
        return $this->last_name;
    }

    public function setLast_name($last_name)
    {
        $this->last_name = $last_name;
    }

    function getBirthdate()
    {
        return $this->birthdate;
    }

    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;
    }

    public function getProfile_picture_url()
    {
        return $this->profile_picture_url;
    }

    public function setProfile_picture_url($profile_picture_url)
    {
        $this->profile_picture_url = $profile_picture_url;
    }

    public function getBiography()
    {
        return $this->biography;
    }

    public function setBiography($biography)
    {
        $this->biography = $biography;
    }

    public function getFacebook_link()
    {
        return $this->facebook_link;
    }

    public function setFacebook_link($facebook_link)
    {
        $this->facebook_link = $facebook_link;
    }

    public function getTwitter_link()
    {
        return $this->twitter_link;
    }

    public function setTwitter_link($twitter_link)
    {
        $this->twitter_link = $twitter_link;
    }

    public function getWebpage()
    {
        return $this->webpage;
    }

    public function setWebpage($webpage)
    {
        $this->webpage = $webpage;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }
    
    public function getGender()
    {
    	return $this->gender;
    }
    
    public function setGender($gender)
    {
    	$this->gender = $gender;
    }
    
    public function getGravatarEmail()
    {
    	return $this->gravatar_email;
    }
    
    public function setGravatarEmail($gravatarEmail)
    {
    	$this->gravatar_email = $gravatarEmail;
    }

    /**
     * Populate from an array.
     *
     * @param array $data            
     */
    public function populate($data = array())
    {
        if (array_key_exists('first_name', $data))
            $this->first_name = $data['first_name'];
        
        if (array_key_exists('last_name', $data))
            $this->last_name = $data['last_name'];
        
        if (array_key_exists('birthdate', $data))
            $this->birthdate = $data['birthdate'];
        
        if (array_key_exists('biography', $data))
            $this->biography = $data['biography'];
        
        if (array_key_exists('profile_picture_url', $data))
            $this->profile_picture_url = $data['profile_picture_url'];
        
        if (array_key_exists('facebook_link', $data))
            $this->facebook_link = $data['facebook_link'];
        
        if (array_key_exists('twitter_link', $data))
            $this->twitter_link = $data['twitter_link'];
        
        if (array_key_exists('webpage', $data))
            $this->webpage = $data['webpage'];
        
        if (array_key_exists('displayname', $data))
        	$this->displayname = $data['displayname'];
        
        if (array_key_exists('gender', $data))
        	$this->gender = $data['gender'];
        
        if (array_key_exists('gravatar_email', $data))
        	$this->gravatar_email = $data['gravatar_email'];
        
        return $data;
    }

    public function getArrayCopy()
    {
        /*return array(
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'birthdate' => $this->birthdate,
            'biography' => $this->biography,
            'profile_picture_url' => $this->profile_picture_url,
            'facebook_link' => $this->facebook_link,
            'twitter_link' => $this->twitter_link,
            'webpage' => $this->webpage,
            'countries' => $this->countries,
            'displayname' => $this->displayname
        // 'user'=>$this->user
                );*/
        $data = get_object_vars($this);
        return $data;
    }

    public function getDisplayName()
    {
        return $this->displayname;
    }

    public function setDisplayName($displayName)
    {
        $this->displayname = $displayName;
    }
}
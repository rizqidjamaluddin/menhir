<?php  namespace Menhir\Security; 
interface Policy 
{
    /**
     * @return bool
     */
    public function evaluate();
}
<?php  namespace Menhir\Security;
class DecisionMaker 
{
    /**
     * @var Policy[]
     */
    protected $considerations = [];

    public function consider(Policy $policy)
    {
        $this->considerations[] = $policy;
    }

    public function decide()
    {
        $result = true;

        foreach ($this->considerations as $consideration) {
            $evaluation = $consideration->evaluate();
            if (!$evaluation) {
                $result = false;
            }
        }

        $this->reset();

        return $result;
    }

    protected function reset()
    {
        $this->considerations = [];
    }
}
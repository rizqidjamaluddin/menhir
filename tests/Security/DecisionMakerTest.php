<?php
use Menhir\Security\DecisionMaker;
use Menhir\Security\Policy;

class DecisionMakerTest extends TestCase
{
    /**
     * @test
     */
    public function it_only_passes_if_all_policies_return_true()
    {
        // no policies also return true, useful for placeholder decision makers
        $decider = new DecisionMaker();
        $this->assertTrue($decider->decide());

        $decider = new DecisionMaker();
        $decider->consider($this->passingPolicy());
        $this->assertTrue($decider->decide());

        $decider = new DecisionMaker();
        $decider->consider($this->passingPolicy());
        $decider->consider($this->passingPolicy());
        $this->assertTrue($decider->decide());

        $decider = new DecisionMaker();
        $decider->consider($this->failingPolicy());
        $this->assertFalse($decider->decide());

        $decider = new DecisionMaker();
        $decider->consider($this->passingPolicy());
        $decider->consider($this->failingPolicy());
        $this->assertFalse($decider->decide());
    }

    /**
     * This helps avoid accidental situations where a singleton decision maker persists and returns false negatives in
     * subsequent usage.
     *
     * @test
     */
    public function it_resets_after_making_a_decision()
    {
        $decider = new DecisionMaker();
        $decider->consider($this->failingPolicy());
        $decider->decide();

        $decider->consider($this->passingPolicy());
        $this->assertTrue($decider->decide());
    }

    /**
     * @test
     */
    public function it_can_reveal_a_result_explanation()
    {

    }

    /**
     * @return Policy
     */
    protected function passingPolicy()
    {
        $mock = Mockery::mock(Policy::class);
        $mock->shouldReceive('evaluate')->andReturn(true);
        return $mock;
    }

    /**
     * @return Policy
     */
    protected function failingPolicy()
    {
        $mock = Mockery::mock(Policy::class);
        $mock->shouldReceive('evaluate')->andReturn(false);
        return $mock;
    }

}
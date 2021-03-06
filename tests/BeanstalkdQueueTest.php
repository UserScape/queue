<?php

use Mockery as m;

class BeanstalkdQueueTest extends PHPUnit_Framework_TestCase {

	public function tearDown()
	{
		m::close();
	}


	public function testPushProperlyPushesJobOntoBeanstalkd()
	{
		$queue = new Illuminate\Queue\BeanstalkdQueue(m::mock('Pheanstalk'), 'default');
		$pheanstalk = $queue->getPheanstalk();
		$pheanstalk->shouldReceive('useTube')->once()->with('stack')->andReturn($pheanstalk);
		$pheanstalk->shouldReceive('useTube')->once()->with('default')->andReturn($pheanstalk);
		$pheanstalk->shouldReceive('put')->twice()->with(serialize(array('job' => 'foo', 'data' => array('data'))));

		$queue->push('foo', array('data'), 'stack');
		$queue->push('foo', array('data'));
	}


	public function testPopProperlyPopsJobOffOfBeanstalkd()
	{
		$queue = new Illuminate\Queue\BeanstalkdQueue(m::mock('Pheanstalk'), 'default');
		$queue->setContainer(m::mock('Illuminate\Container'));
		$pheanstalk = $queue->getPheanstalk();
		$pheanstalk->shouldReceive('watchOnly')->once()->with('default')->andReturn($pheanstalk);
		$job = m::mock('Pheanstalk_Job');
		$pheanstalk->shouldReceive('reserve')->once()->andReturn($job);

		$result = $queue->pop();

		$this->assertInstanceOf('Illuminate\Queue\Jobs\BeanstalkdJob', $result);
	}

}
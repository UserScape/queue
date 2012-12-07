<?php namespace Illuminate\Queue\Jobs;

use Pheanstalk;
use Laravel\IoC;
use Pheanstalk_Job;

class BeanstalkdJob extends Job {

	/**
	 * The Pheanstalk instance.
	 *
	 * @var Pheanstalk
	 */
	protected $pheanstalk;

	/**
	 * The Pheanstalk job instance.
	 *
	 * @var Pheanstalk_Job
	 */
	protected $job;

	/**
	 * Create a new job instance.
	 *
	 * @param  Pheanstalk  $pheanstalk
	 * @param  Pheanstalk_Job  $job
	 * @return void
	 */
	public function __construct(Pheanstalk $pheanstalk,
                                Pheanstalk_Job $job)
	{
		$this->job = $job;
		$this->container = $container;
		$this->pheanstalk = $pheanstalk;
	}

	/**
	 * Fire the job.
	 *
	 * @return void
	 */
	public function fire()
	{
		$payload = unserialize($this->job->getData());

		// Once we have the message payload, we can create the given class and fire
		// it off with the given data. The data is in the messages serialized so
		// we will unserialize it and pass into the jobs in its original form.
		$instance = IoC::resolve($payload['job']);

		$instance->fire($this, $payload['data']);
	}

	/**
	 * Delete the job from the queue.
	 *
	 * @return void
	 */
	public function delete()
	{
		$this->pheanstalk->delete($this->job);
	}

	/**
	 * Release the job back into the queue.
	 *
	 * @param  int   $delay
	 * @return void
	 */
	public function release($delay = 0)
	{
		$priority = Pheanstalk::DEFAULT_PRIORITY;

		$this->pheanstalk->release($this->job, $priority, $delay);
	}

	/**
	 * Get the underlying Pheanstalk instance.
	 *
	 * @return Pheanstalk
	 */
	public function getPheanstalk()
	{
		return $this->pheanstalk;
	}

	/**
	 * Get the underlying Pheanstalk job.
	 *
	 * @return Pheanstalk_Job
	 */
	public function getPheanstalkJob()
	{
		return $this->job;
	}

}
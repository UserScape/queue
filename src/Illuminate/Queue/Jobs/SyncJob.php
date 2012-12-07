<?php namespace Illuminate\Queue\Jobs;

use Laravel\IoC;

class SyncJob extends Job {

	/**
	 * The class name of the job.
	 *
	 * @var string
	 */
	protected $job;

	/**
	 * The queue message data.
	 *
	 * @var string
	 */
	protected $data;

	/**
	 * Create a new job instance.
	 *
	 * @param  string  $job
	 * @param  string  $data
	 * @return void
	 */
	public function __construct($job, $data = '')
	{
		$this->job = $job;
		$this->data = $data;
	}

	/**
	 * Fire the job.
	 *
	 * @return void
	 */
	public function fire()
	{
		$this->instance = IoC::resolve($this->job);

		$this->instance->fire($this, unserialize($this->data));
	}

	/**
	 * Delete the job from the queue.
	 *
	 * @return void
	 */
	public function delete()
	{
		//
	}

	/**
	 * Release the job back into the queue.
	 *
	 * @param  int   $delay
	 * @return void
	 */
	public function release($delay = 0)
	{
		//
	}

}
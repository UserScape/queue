<?php namespace Illuminate\Queue\Jobs;

use IronMQ;
use Illuminate\Container;

class IronJob extends Job {

	/**
	 * The IoC container instance.
	 *
	 * @var Illuminate\Container
	 */
	protected $container;

	/**
	 * The IronMQ instance.
	 *
	 * @var IronMQ
	 */
	protected $iron;

	/**
	 * The IronMQ message instance.
	 *
	 * @var array
	 */
	protected $job;

	/**
	 * The name of the queue the job came from.
	 *
	 * @var string
	 */
	protected $queue;

	/**
	 * Create a new job instance.
	 *
	 * @param  Illuminate\Container  $container
	 * @param  IronMQ  $iron
	 * @param  array   $job
	 * @param  string  $queue
	 * @return void
	 */
	public function __construct(Container $container,
                                IronMQ $pheanstalk,
                                array $job,
                                $queue)
	{
		$this->job = $job;
		$this->queue = $queue;
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
		$payload = unserialize($this->job->body);

		// Once we have the message payload, we can create the given class and fire
		// it off with the given data. The data is in the messages serialized so
		// we will unserialize it and pass into the jobs in its original form.
		$this->instance = $this->container->make($payload['job']);

		$this->instance->fire($this, $payload['data']);
	}

	/**
	 * Delete the job from the queue.
	 *
	 * @return void
	 */
	public function delete()
	{
		$this->iron->deleteMessage($this->queue, $this->job->id);
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

	/**
	 * Get the IoC container instance.
	 *
	 * @return Illuminate\Container
	 */
	public function getContainer()
	{
		return $this->container;
	}

	/**
	 * Get the underlying IronMQ instance.
	 *
	 * @return IronMQ
	 */
	public function getIron()
	{
		return $this->iron;
	}

	/**
	 * Get the underlying IronMQ job.
	 *
	 * @return array
	 */
	public function getIronJob()
	{
		return $this->job;
	}

}
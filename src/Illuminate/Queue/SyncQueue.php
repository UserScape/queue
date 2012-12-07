<?php namespace Illuminate\Queue;

class SyncQueue extends Queue implements QueueInterface {

	/**
	 * Push a new job onto the queue.
	 *
	 * @param  string  $job
	 * @param  mixed   $data
	 * @param  string  $queue
	 * @return void
	 */
	public function push($job, $data = '', $queue = null)
	{
		$this->resolveJob($job, $data)->fire();
	}

	/**
	 * Pop the next job off of the queue.
	 *
	 * @param  string  $queue
	 * @return Illuminate\Queue\Jobs\Job|null
	 */
	public function pop($queue = null) {}

	/**
	 * Resolve a Sync job instance.
	 *
	 * @param  string  $job
	 * @param  string  $data
	 * @return Illuminate\Queue\Jobs\SyncJob
	 */
	protected function resolveJob($job, $data)
	{
		return new Jobs\SyncJob($job, $data);
	}

}
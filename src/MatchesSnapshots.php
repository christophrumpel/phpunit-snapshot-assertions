<?php

namespace Spatie\Snapshots;

trait MatchesSnapshots
{
    public function assertMatchesSnapshot($actual, $type = 'var', $methodTrace = null)
    {
        $snapshot = Snapshot::forTestMethod(
            $methodTrace ?? debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2)[1],
            $type
        );

        if (! $snapshot->exists()) {
            $snapshot->create($actual);

            return $this->markTestIncomplete("Snapshot created for {$snapshot->id()}");
        }

        if ($type === 'xml') {
            return $this->assertXmlStringEqualsXmlString($snapshot->get(), $actual);
        }

        if ($type === 'json') {
            return $this->assertJsonStringEqualsJsonString($snapshot->get(), $actual);
        }

        $this->assertEquals($snapshot->get(), $actual);
    }

    public function assertMatchesXmlSnapshot($actual)
    {
        $this->assertMatchesSnapshot($actual, 'xml', debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2)[1]);
    }

    public function assertMatchesJsonSnapshot($actual)
    {
        $this->assertMatchesSnapshot($actual, 'json', debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2)[1]);
    }
}

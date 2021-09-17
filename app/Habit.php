<?php

namespace App;

require './utils/uuid.php';

use \DateTimeImmutable;
use \DateInterval;
use \DatePeriod;

class Habit {
    private $name;
    private $duration;
    private $durationFormat = 'D';
    private $dateFormat = 'Y-m-d';
    private $start;
    private $end;
    private $id;

    public function __construct(string $name, array $config)
    {
        $duration = $config['duration'];
        $start = $config['start'] ?? null;
        $id = $config['id'] ?? uuid();

        $this->name = $name;
        $this->id = $id;

        if (is_null($start)) {
            $this->start = new DateTimeImmutable('today');
        } else {
            $this->start = DateTimeImmutable::createFromFormat($this->dateFormat, $start);
        }

        $this->duration = new DateInterval('P' . $duration . $this->durationFormat);
        $this->end = $this->start->add($this->duration);
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setDuration($duration)
    {
        $this->duration = new DateInterval('P' . $duration . $this->durationFormat);
        $this->end = $this->start->add($this->duration);
    }

    public function setStart($start)
    {
        $this->start = DateTimeImmutable::createFromFormat($this->dateFormat, $start);
        $diff = $this->start->diff($this->end)->format($this->durationFormat);
        $this->duration = new DateInterval('P' . $diff . $this->durationFormat);
    }

    public function getStart()
    {
        return $this->start;
    }

    public function setEnd($end)
    {
        $this->end = DateTimeImmutable::createFromFormat($this->dateFormat, $end);
        $diff = $this->start->diff($end)->format($this->durationFormat);
        $this->duration = new DateInterval('P' . $diff . $this->durationFormat);
    }

    public function getEnd()
    {
        return $this->end;
    }

    public function getDatePeriod()
    {
        return new DatePeriod($this->start, new DateInterval('P1D'), $this->end);
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function getDurationFormat()
    {
        return $this->durationFormat;
    }

    public function getId()
    {
        return $this->id;
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'start' => $this->start->format($this->dateFormat),
            'end' => $this->end->format($this->dateFormat),
            'duration' => $this->duration->format('%d'),
            'durationFormat' => $this->durationFormat,
        ];
    }
}
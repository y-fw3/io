<?php

namespace fw3\io\rdbms\query_builder\drivers\general\interfaces\consts;

interface JoinConst
{
    public const JOIN                       = 'JOIN';
    public const INNER_JOIN                 = 'INNER JOIN';
    public const OUTER_JOIN                 = 'OUTER JOIN';
    public const CROSS_JOIN                 = 'CROSS JOIN';
    public const STRAIGHT_JOIN              = 'STRAIGHT_JOIN';
    public const LEFT_JOIN                  = 'LEFT JOIN';
    public const OUTER_LEFT_JOIN            = 'LEFT OUTER JOIN';
    public const RIGHT_JOIN                 = 'RIGHT JOIN';
    public const OUTER_RIGHT_JOIN           = 'RIGHT OUTER JOIN';
    public const NATURAL_JOIN               = 'NATURAL JOIN';
    public const NATURAL_LEFT_JOIN          = 'NATURAL LEFT JOIN';
    public const NATURAL_LEFT_OUTER_JOIN    = 'NATURAL LEFT OUTER JOIN';
    public const NATURAL_RIGHT_JOIN         = 'NATURAL RIGHT JOIN';
    public const NATURAL_RIGHT_OUTER_JOIN   = 'NATURAL RIGHT OUTER JOIN';

    public const JOIN_MAP   = [
        self::JOIN                      => self::JOIN,
        self::INNER_JOIN                => self::INNER_JOIN,
        self::OUTER_JOIN                => self::OUTER_JOIN,
        self::CROSS_JOIN                => self::CROSS_JOIN,
        self::STRAIGHT_JOIN             => self::STRAIGHT_JOIN,
        self::LEFT_JOIN                 => self::LEFT_JOIN,
        self::OUTER_LEFT_JOIN           => self::OUTER_LEFT_JOIN,
        self::RIGHT_JOIN                => self::RIGHT_JOIN,
        self::OUTER_RIGHT_JOIN          => self::OUTER_RIGHT_JOIN,
        self::NATURAL_JOIN              => self::NATURAL_JOIN,
        self::NATURAL_LEFT_JOIN         => self::NATURAL_LEFT_JOIN,
        self::NATURAL_LEFT_OUTER_JOIN   => self::NATURAL_LEFT_OUTER_JOIN,
        self::NATURAL_RIGHT_JOIN        => self::NATURAL_RIGHT_JOIN,
        self::NATURAL_RIGHT_OUTER_JOIN  => self::NATURAL_RIGHT_OUTER_JOIN,
    ];
}

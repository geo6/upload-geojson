<?php

declare(strict_types=1);

namespace App\Validator;

use Exception;

class GeoJSON
{
    const TYPES = [
        'FeatureCollection',
        'Feature',
    ];

    const GEOMETRY_TYPES = [
        'Point',
        'MultiPoint',
        'LineString',
        'MultiLineString',
        'Polygon',
        'MultiPolygon',
        'GeometryCollection',
    ];

    private $content;
    private $warnings = [];
    private $error;

    public function __construct(string $content)
    {
        $json = json_decode($content);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error = 'Invalid JSON file : '.json_last_error_msg();
        } else {
            try {
                if (!isset($json->type) || !in_array($json->type, self::TYPES)) {
                    throw new Exception('Property "type" is missing or invalid.');
                }

                if (isset($json->bbox) && (
                    !is_array($json->bbox) ||
                    (count($json->bbox) !== 4 && count($json->bbox) !== 6)
                )) {
                    throw new Exception('Property "bbox" is invalid.');
                }

                if (isset($json->crs)) {
                    $this->warnings[] = 'Property "crs" is deprecated.';

                    if (!isset($json->crs->type, $json->crs->properties, $json->crs->properties->name) ||
                        $json->crs->type !== 'name' ||
                        (
                            $json->crs->properties->name !== 'urn:ogc:def:crs:OGC::CRS84' &&
                            $json->crs->properties->name !== 'urn:ogc:def:crs:OGC:1.3:CRS84'
                        )
                    ) {
                        throw new Exception(
                            sprintf(
                                'Invalid "crs" property : %s.'.PHP_EOL.
                                'Property "crs" is deprecated and only WGS 84 is allowed.',
                                $json->crs->properties->name
                            )
                        );
                    }
                }

                if (!isset($json->title)) {
                    $this->warnings[] = 'Missing property "title".';
                }
                /*
                if (!isset($json->description)) {
                    $this->warnings[] = 'Missing property "description".';
                }
                */

                switch ($json->type) {
                    case 'Feature':
                        $this->validateFeature($feature);
                        break;

                    case 'FeatureCollection':
                        if (!isset($json->features)) {
                            throw new Exception('Missing property "features".');
                        }

                        if (empty($json->features)) {
                            $this->warnings[] = '"FeatureCollection" is empty.';
                        } else {
                            foreach ($json->features as $feature) {
                                $this->validateFeature($feature);
                            }
                        }
                        break;
                }
            } catch (Exception $e) {
                $this->error = sprintf('Invalid GeoJSON file : %s', $e->getMessage());
            }
        }
    }

    public function isValid(): bool
    {
        return is_null($this->error);
    }

    public function getWarnings(): array
    {
        return $this->warnings;
    }
    public function getError(): string
    {
        return $this->error;
    }

    private function validateFeature(object $feature): bool
    {
        if (!isset($feature->id)) {
            $this->warnings[] = 'Missing property "id" for at least one feature.';
        }
        if (!isset($feature->properties)) {
            throw new Exception('Missing property "properties" for at least one feature.');
        }
        if (!isset($feature->geometry)) {
            throw new Exception('Missing property "geometry" for at least one feature.');
        }

        if (isset($feature->bbox) && (
            !is_array($feature->bbox) ||
            (count($feature->bbox) !== 4 && count($feature->bbox) !== 6)
        )) {
            throw new Exception('Invalid "bbox" property for at least one feature.');
        }

        $this->validateGeometry($feature->geometry);

        return true;
    }

    private function validateGeometry(object $geometry): bool
    {
        if (!isset($geometry->type)) {
            throw new Exception('Missing property "type" for at least one geometry.');
        }
        if (!isset($geometry->coordinates)) {
            throw new Exception('Missing property "coordinates" for at least one geometry.');
        }
        if (!in_array($geometry->type, self::GEOMETRY_TYPES)) {
            throw new Exception(sprintf('Invalid property "type" for at least one geometry : %s.', $geometry->type));
        }

        return true;
    }
}

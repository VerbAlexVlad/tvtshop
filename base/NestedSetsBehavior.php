<?php

namespace app\base;

class NestedSetsBehavior extends \paulzi\nestedsets\NestedSetsBehavior
{
     /**
     * Populate children relations for self and all descendants
     * @param int $depth = null
     * @param string|array $with = null
     * @return static
     */
    public function populateTree($depth = null, $with = null, $select = null, $array = false)
    {
              /** @var ActiveRecord[]|static[] $nodes */
        $query = $this->getDescendants($depth);

        if ($select) {
            $query->select($select);
        }
        if ($with) {
            $query->with($with);
        }
      
        if($array) {
            $nodes = $query->asArray()->all();
            $key = $this->owner->getAttribute($this->leftAttribute);
            $relates = [];
            $parents = [$key];
            $prev = $this->owner->getAttribute($this->depthAttribute);
          
            foreach($nodes as $node)
            {
                $level = $node['depth'];
                if ($level <= $prev) {
                    $parents = array_slice($parents, 0, $level - $prev - 1);
                }

                $key = end($parents);
                if (!isset($relates[$key])) {
                    $relates[$key] = [];
                }
                $relates[$key][] = $node;

                $parents[] = $node['lft'];
                $prev = $level;
            }

            $ownerDepth = $this->owner->getAttribute($this->depthAttribute);
            $nodes[] = $this->owner;
            foreach ($nodes as $id=>$node) {
                if(is_array($node)) {
                    $key = $node['lft'];
                    if (isset($relates[$key])) {
                        $nodes[$id]['children'] = $relates[$key];
                    } elseif ($depth === null || $ownerDepth + $depth > $node['depth']) {
                        $node['children'] = [];
                    }
                }
            }
        }

        $nodes = $query->all();

        $key = $this->owner->getAttribute($this->leftAttribute);
        $relates = [];
        $parents = [$key];
        $prev = $this->owner->getAttribute($this->depthAttribute);
        foreach($nodes as $node)
        {
            $level = $node->getAttribute($this->depthAttribute);
            if ($level <= $prev) {
                $parents = array_slice($parents, 0, $level - $prev - 1);
            }

            $key = end($parents);
            if (!isset($relates[$key])) {
                $relates[$key] = [];
            }
            $relates[$key][] = $node;

            $parents[] = $node->getAttribute($this->leftAttribute);
            $prev = $level;
        }

        $ownerDepth = $this->owner->getAttribute($this->depthAttribute);
        $nodes[] = $this->owner;
        foreach ($nodes as $node) {
            $key = $node->getAttribute($this->leftAttribute);
            if (isset($relates[$key])) {
                $node->populateRelation('children', $relates[$key]);
            } elseif ($depth === null || $ownerDepth + $depth > $node->getAttribute($this->depthAttribute)) {
                $node->populateRelation('children', []);
            }
        }

        return $this->owner;
    }
  
}
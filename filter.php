<?php

$data = [
	"name" => "Root",
	"branches" => [
	[
			"name" => "Root Branch 1",
			"branches" => [
				[
					"name" => "Branch 2",
					"branches" => [],
				],
				[
					"name" => "Branch 3",
					"branches" => [
						[
							"name" => "Branch 4",
							"branches" => [],
						],
						[
							"name" => "Branch 5",
							"branches" => []
						]
					]
				],
			]
		],
		[
			"name" => "Root Branch 2",
			"branches" => [
				[
					"name" => "Branch 1",
					"branches" => [
						[
							"name" => "Branch 2",
							"branches" => [
								[
									"name" => "Special Branch",
									"branches" => [],
								]
							]
						],
						[
							"name" => "One more specific branch",
							"branches" => [],
						]
					]
				],
			]
		]
	]
];

//filter callback to check if branch is apply with filter
function filterCallback(Branch $branch, $search)
{
	if (!is_array($search) && !is_string($search))
		die("The search filter is not correct!");

	if (is_array($search)) {
		$i = 0;

		while(isset($search[$i])) {
			if (strpos($branch->name, $search[$i]) !== false)
				return true;

			$i++;
		}

		return false;
	}

	return strpos($branch->name, $search) !== false;
}

//filter tree based on search filter
function filter(Branch $parentBranch, $search) 
{
	if (count($parentBranch->branches) > 0) {
		$parentBranch->branches = array_filter($parentBranch->branches, function ($childBranch) use ($search) {
			return filter($childBranch, $search);
		});

		if (count($parentBranch->branches) > 0)
			return $parentBranch;

		return false;
	}

	return filterCallback($parentBranch, $search);
}

//in real world name and branches has to be protected 
//and has getters and setters
class Branch
{
	public $name;
	public $branches = [];

	public function __construct(string $name) 
	{
		$this->name = $name;
	}

	public function addBranch(Branch $branch) 
	{
		$this->branches = array_merge([$branch], $this->branches);
	}
}

//grow the three :)
function growTree(array $seed, Branch $tree)
{
	foreach ($seed['branches'] as $branch) {
		$branchObj = new Branch($branch['name']);

		$tree->addBranch($branchObj);

		if (count($branch['branches']) > 0)
			growTree($branch, $branchObj);
	}
}

//build tree recursively
$tree = new Branch('Root');
growTree($data, $tree);

$search = ["One"];

$tree->branches = array_filter($tree->branches, function ($branch) use ($search) {
	return filterRecursively($branch, $search);
});

print_r($tree->branches);
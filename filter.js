let tree = {
	name: "Root",
	branches: [
	{
			name: "Root Branch 1",
			branches: [
				{
					name: "Branch 2",
					branches: [],
				},
				{
					name: "Branch 3",
					branches: [
						{
							name: "Branch 4",
							branches: [],
						},
						{
							name: "Branch 5",
							branches: []
						}
					]
				},
			]
		},
		{
			name: "Root Branch 2",
			branches: [
				{
					name: "Branch 1",
					branches: [
						{
							name: "Branch 2",
							branches: [
								{
									name: "Special Branch",
									branches: [],
								}
							]
						},
						{
							name: "One more specific branch",
							branches: [],
						}
					]
				},
			]
		}
	]
}

//filter callback which decides if branch is apply with search filter
function filterCallback(branch, search) {
	if (Array.isArray(search)) {
		let i = 0

		while(search[i]) {
			if (branch.name.search(search[i] + "") != -1)
				return true;

			i++
		}

		return false
	}

	return branch.name.search(search) != -1
}

//filter recursibely to find all branches which apply with search filter
function filter(parentBranch, search) {
	if (parentBranch.branches.length > 0) {
		parentBranch.branches = parentBranch.branches.filter(childBranch => filter(childBranch, search))

		if (filterCallback(parentBranch, search) || parentBranch.branches.length > 0)
			return parentBranch;

		return false;
	}

	return filterCallback(parentBranch, search)
}

//set search filter
const search = ['One']

//check recursively all branches and filter them
tree.branches = tree.branches.filter(branch => filter(branch, search));

console.log(JSON.stringify(tree));


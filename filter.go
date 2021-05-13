package main

import f "fmt"
import j "encoding/json"
import s "strings"

type Branch struct {
	Name string
	Branches []Branch
}

//callback which decides is branch applied with filter or not..
func filterCallback(branch Branch, search []string) bool {
	for i := 0; i < len(search); i++ {
		if s.Contains(branch.Name, search[i]) {	
			return true
		}
	}

	return false
}

//filter tree recursively based on provided search filter
func filter(parentBranch Branch, search []string) (bool, Branch) {
	if len(parentBranch.Branches) > 0 {
		var branches []Branch

		for i := 0; i < len(parentBranch.Branches); i++ {
			applyToFilter, filteredBranch := filter(parentBranch.Branches[i], search);

			if applyToFilter == true {
				branches = append(branches, filteredBranch)
			}
		}

		if len(branches) > 0 {
			parentBranch.Branches = branches
			return true, parentBranch
		}

		return false, parentBranch
	}

	return filterCallback(parentBranch, search), parentBranch
}

func main() {

	//init tree and create json in string
	var rootBranch Branch
	branchesJson := `{"name":"Root","branches":[{"name":"Root Branch 1","branches":[{"name":"Branch 2","branches":[]},{"name":"Branch 3","branches":[{"name":"Branch 4","branches":[]},{"name":"Branch 5","branches":[]}]}]},{"name":"Root Branch 2","branches":[{"name":"Branch 1","branches":[{"name":"Branch 2","branches":[{"name":"Special Branch","branches":[]}]},{"name":"One more specific branch","branches":[]}]}]}]}`

	//parse json to create tree
	j.Unmarshal([]byte(branchesJson), &rootBranch)

	var branches []Branch
	search := []string{"One"}

	for i := 0; i < len(rootBranch.Branches); i++ {
		applyToFilter,filteredBranch := filter(rootBranch.Branches[i], search);

		if applyToFilter == true {
			branches = append(branches, filteredBranch)
		}
	}

	rootBranch.Branches = branches

	f.Println(rootBranch)
}
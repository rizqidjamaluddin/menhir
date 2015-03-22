# Menhir

Menhir is a collection of commonly used elements for developing medium-large web applications with Laravel.

## Controller Layer

## Command Bus

## Authorization

```
class UserBanningAuthorizer {
	public function decide($actingUser, $targetUser) {
		$decider = new ConsensusDecider;
		$decider->consider(new AclPolicy($actingUser, 'users.ban'));
		$decider->consider(new LowerOrEqualRankPolicy($actingUser, $targetUser));
		return $decider->decide();
	}
}
```

## Repository Layer
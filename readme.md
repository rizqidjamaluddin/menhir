# Menhir

Menhir is a collection of commonly used elements for developing medium-large web applications with Laravel.

## Controller Layer

## Command Bus

## Authorization

Menhir suggests using an authorizer class model - create a specific class to protect each sensitive operation (analogous to command handlers).

This class is responsible for determining if the user is allowed to execute that operation. Menhir provides a Decision Maker and Policy helper; tell the Decision Maker to consider a bunch of policies, and the Decision Maker will only pass if the policies pass. Menhir provides some basic policies, but you're free (and should) add your own.

Each policy corresponds to a piece of permission logic; for instance, "pass the ACL check" is a policy. Rules regarding "can the user do so and so" should be represented as a policy, away from application logic. You can also call them "rules", or "requirements".

As usual, feel free to mold these classes to fit your idea of how it works. Custom policies just implement the Policy interface and _must_ return `true` on the `evaluate` method on success.

Other common policies:

- Only users with over 100 reputation can make a post
- Only participants of a conversation may make replies
- A user can edit their own threads, but not others'
- A moderator can lock discussions, but only in their sub-forum

Sometimes we'll want to have an option between two or more policies - for instance, a user can edit a post if they're a moderator, or if they wrote the post. Group them up with an `AtLeastOnePolicy` and it'll pass if at least one of them say yes:

```
$decisionMaker->consider(new AtLeastOnePolicy([new UserOwnsPostPolicy($post, $user), new IsAModeratorPolicy($user)]);
```

Decision Makers are also handy for attaching additional logic as well, such as throwing events or logging how a user was granted access to something. `$decisionMaker->explain()` will get a report on how the decision process panned out.

```
class UserBanningAuthorizer {
	public function decide($actingUser, $targetUser) {
		$decider = new DecisionMaker;
		$decider->consider(new AclPolicy($actingUser, 'users.ban'));
		$decider->consider(new LowerOrEqualRankPolicy($actingUser, $targetUser));
		return $decider->decide();
	}
}
```

## Repository Layer
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## GitHub Weekly Commits API (Laravel 10)

 This project is a Laravel 10 REST API that fetches commit data from the GitHub REST API for a given public repository and groups commits by calendar week.

### Example public repository

You can test your implementation using a valid public GitHub repository such as:
```bash
facebook/react
```

Other examples:
```bash
symfony/symfony
laravel/laravel
```


You should be able to fetch commits from these without authentication (rate limits may apply).

### Example request (curl)

After running your application locally, you should be able to perform a request such as:
```bash
curl "http://localhost:8000/facebook/react?since=2024-01-01&until=2024-02-01"
```

Or, if you expose the server on another port:
```bash
curl "http://localhost:8080/facebook/react?since=2024-01-01&until=2024-02-01"
```

## Installation
- Clone the repository

- Run composer install

- Run php artisan serve

- Access the API using browser, curl, or Postman

## Tests

Automated tests were not implemented in this version due to time constraints.

## Possible Future Improvements
- Possible Future Improvements

- Add unit and feature tests

- Handle GitHub API rate limiting

- Add caching for API responses

- Support authenticated GitHub requests

- Improve handling of year and week combinations

## API Endpoint

GET /api/{user}/{repository}

Example request:
http://localhost/github-weekly-commits/public/api/facebook/react?since=2024-01-01&until=2024-02-10


## Example request:

http://localhost/github-weekly-commits/public/api/facebook/react?since=2024-01-01&until=2024-01-07

- Response

[
    {
        "week": 1,
        "count": 4,
        "commits": [
            {
                "sha": "7ca3b004aee982ee637dc06ccb5fe890c9f6e5ba",
                "node_id": "C_kwDOAJy2KtoAKDdjYTNiMDA0YWVlOTgyZWU2MzdkYzA2Y2NiNWZlODkwYzlmNmU1YmE",
                "commit": {
                    "author": {
                        "name": "Joe Savona",
                        "email": "joesavona@fb.com",
                        "date": "2024-01-03T18:47:47Z"
                    },
                    "committer": {
                        "name": "Joe Savona",
                        "email": "joesavona@fb.com",
                        "date": "2024-01-03T18:47:47Z"
                    },
                    "message": "Early branch with new type inference foundation\n\nIt's starting to get complex just with a couple of extra\npasses — we either need to substantially extend the HIR or (as i've done so far)\npass information from early passes to later ones. This PR changes things so that\nvery early in the babel plugin we fork into a separate mode. Forest has\nits own `compileProgram()` equivalent, its own pipeline, its own codegen, etc.",
                    "tree": {
                        "sha": "9e4d08aefb5c7fdbb37a2d4639a571578926cc6f",
                        "url": "https://api.github.com/repos/facebook/react/git/trees/9e4d08aefb5c7fdbb37a2d4639a571578926cc6f"
                    },
                    "url": "https://api.github.com/repos/facebook/react/git/commits/7ca3b004aee982ee637dc06ccb5fe890c9f6e5ba",
                    "comment_count": 0,
                    "verification": {
                        "verified": false,
                        "reason": "unsigned",
                        "signature": null,
                        "payload": null,
                        "verified_at": null
                    }
                },
                "url": "https://api.github.com/repos/facebook/react/commits/7ca3b004aee982ee637dc06ccb5fe890c9f6e5ba",
                "html_url": "https://github.com/facebook/react/commit/7ca3b004aee982ee637dc06ccb5fe890c9f6e5ba",
                "comments_url": "https://api.github.com/repos/facebook/react/commits/7ca3b004aee982ee637dc06ccb5fe890c9f6e5ba/comments",
                "author": {
                    "login": "josephsavona",
                    "id": 6425824,
                    "node_id": "MDQ6VXNlcjY0MjU4MjQ=",
                    "avatar_url": "https://avatars.githubusercontent.com/u/6425824?v=4",
                    "gravatar_id": "",
                    "url": "https://api.github.com/users/josephsavona",
                    "html_url": "https://github.com/josephsavona",
                    "followers_url": "https://api.github.com/users/josephsavona/followers",
                    "following_url": "https://api.github.com/users/josephsavona/following{/other_user}",
                    "gists_url": "https://api.github.com/users/josephsavona/gists{/gist_id}",
                    "starred_url": "https://api.github.com/users/josephsavona/starred{/owner}{/repo}",
                    "subscriptions_url": "https://api.github.com/users/josephsavona/subscriptions",
                    "organizations_url": "https://api.github.com/users/josephsavona/orgs",
                    "repos_url": "https://api.github.com/users/josephsavona/repos",
                    "events_url": "https://api.github.com/users/josephsavona/events{/privacy}",
                    "received_events_url": "https://api.github.com/users/josephsavona/received_events",
                    "type": "User",
                    "user_view_type": "public",
                    "site_admin": false
                },
                "committer": {
                    "login": "josephsavona",
                    "id": 6425824,
                    "node_id": "MDQ6VXNlcjY0MjU4MjQ=",
                    "avatar_url": "https://avatars.githubusercontent.com/u/6425824?v=4",
                    "gravatar_id": "",
                    "url": "https://api.github.com/users/josephsavona",
                    "html_url": "https://github.com/josephsavona",
                    "followers_url": "https://api.github.com/users/josephsavona/followers",
                    "following_url": "https://api.github.com/users/josephsavona/following{/other_user}",
                    "gists_url": "https://api.github.com/users/josephsavona/gists{/gist_id}",
                    "starred_url": "https://api.github.com/users/josephsavona/starred{/owner}{/repo}",
                    "subscriptions_url": "https://api.github.com/users/josephsavona/subscriptions",
                    "organizations_url": "https://api.github.com/users/josephsavona/orgs",
                    "repos_url": "https://api.github.com/users/josephsavona/repos",
                    "events_url": "https://api.github.com/users/josephsavona/events{/privacy}",
                    "received_events_url": "https://api.github.com/users/josephsavona/received_events",
                    "type": "User",
                    "user_view_type": "public",
                    "site_admin": false
                },
                "parents": [
                    {
                        "sha": "bd37fbe06acca8e11a00ea48752fb5e86146f42f",
                        "url": "https://api.github.com/repos/facebook/react/commits/bd37fbe06acca8e11a00ea48752fb5e86146f42f",
                        "html_url": "https://github.com/facebook/react/commit/bd37fbe06acca8e11a00ea48752fb5e86146f42f"
                    }
                ]
            }
        ]
    }
]




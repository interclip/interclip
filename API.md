# Interclip API

There is a very simple API for anybody to use. Remember, that clips only last for one month! (as of [v3.2.4](https://github.com/aperta-principium/Interclip/releases/tag/v3.2.4))
## Create clips
### Example queries
POST
`https://interclip.app/includes/api`
body:
```json
{
  "url": "https://duckduckgo.com"
}
```
GET
`https://interclip.app/includes/api?url=https://flutter.dev/`

### Example responce
```json
{
  "status": "success",
  "result": "k02yl"
}
```

## Get a clip
### Example queries
POST
`https://interclip.app/includes/get-api`
body:
```json
{
  "code": "tasks"
}
```
GET
`https://interclip.app/includes/get-api?code=tasks`

### Example responce
```json
{
  "status": "success",
  "result": "https://taskord.com/"
}
```

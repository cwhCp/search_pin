{%

local mysql = require "resty.mysql"
local json = require "cjson"
local param = _APPWEB
local pClassID = param.classid
local pAreaID = param.areaid

if pClassID == nil or pAreaID == nil then
	print(json.encode("param err: "))
	return nil
end

local db, err = mysql:new()
if not db then
	print_r("failed to instantiate mysql: ", err)
	return
end

db:set_timeout(1000)
local ok, err, errno, sqlstate = db:connect{
	host = "127.0.0.1",
	port = 3307,
	database = "bigD",
	user = "root",
	password = "vnetAdmin123",
	max_packet_size = 1024 * 1024 
}
if not ok then
	ngx.say("failed to connect: ", err, ": ", errno, " ", sqlstate)
	return
end

local sql_getLoc = [[select fun, state, timeOut, weight, buildMAC, coordinateX, coordinateY, coordinateZ from state where pClassID = '%d' and pAreaID = '%d']]

local res, err, errno, sqlstate =
db:query(string.format(sql_getLoc, pClassID, pAreaID))
if not res then
	ngx.say("bad result: ", err, ": ", errno, ": ", sqlstate, ".")
	return
end

ngx.say(json.encode(res))

%}

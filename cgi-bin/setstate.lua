{%

local mysql = require "resty.mysql"
local json = require "cjson"
local param = _APPWEB

local pClassID = param.pClassID
local pAreaID = param.pAreaID

if pClassID == nil or pAreaID == nil then
	print(json.encode("param error: ") .. json.encode(param))
	return nil
end

local origk = {
	coordinateX = true,
	coordinateY = true,
	coordinateZ = true,
	fun = true,
	state = true,
	timeOut = true,
	weight = true,
	buildMAC = true,
	-- pClassID = true,
	-- pAreaID = true
}

local function mysql_do( cmd )
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

	local res, err, errno, sqlstate =
	-- db:query(string.format(sql_getLoc, pattern, cmd, pClassID, pAreaID))
	db:query(cmd)
	if not res then
		ngx.say("bad result: ", err, ": ", errno, ": ", sqlstate, ".")
		return nil
	end
end

if param.checkclean == "cleanup" then
	local truncate = "truncate table finger"
	mysql_do(truncate)
	truncate = "truncate table fingerDev"
	mysql_do(truncate)
	return
end

if param.fun == "1" and param.state ~= "3" then
	param.state = "1"
end

for key, _ in pairs(origk) do
	local pattern = "update state set "
	if param[key] ~= nil then
		pattern = pattern .. key .. " = '" .. param[key] .. "' "
		pattern = pattern .. "where pClassID = '" .. pClassID .. "' and pAreaID = '" .. pAreaID .. "'"
		mysql_do(pattern)
		print(json.encode(pattern) .. '\n')
	end
end

ngx.say(json.encode("ok"))
-- ngx.say(json.encode(string.format(pattern, cmd)))

%}

CREATE TABLE RequestLogs (
    Id INT IDENTITY(1,1) PRIMARY KEY,
    Endpoint NVARCHAR(255),
    Method NVARCHAR(10),
    ResponseData NVARCHAR(MAX),
    CreatedAt DATETIME DEFAULT GETDATE()
);

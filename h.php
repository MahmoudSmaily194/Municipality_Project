<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
.magic-skeleton {
  width: 100%;
  height: 150px;
  border-radius: 14px;

  background: linear-gradient(
    120deg,
    #5f6cff,
    #5fdfff,
    #8bffe8,
    #d18bff,
    #5f6cff
  );
  background-size: 250% 100%;
  animation: magicShimmer 2.5s infinite;
  filter: brightness(1.1);
}


@keyframes magicShimmer {
  0%   { background-position: 0% 50%; }
  50%  { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

</style>
<body>
<div class="magic-skeleton"></div>
</body>
</html>
from math import cos, sin, asin, radians, sqrt

def haversine(origin, far):
    """
    input: tuples
    output: float
    returns the distance in km between the two passed points
    """
    lat_o, lon_o = radians(origin[0]), radians(origin[1])
    lat_f, lon_f = radians(far[0]), radians(far[1])
    r = 6378
    phi = lat_f - lat_o
    delta = lon_f - lon_o
    return 2 * r * asin(sqrt(sin(phi/2)**2 + cos(lat_o)*cos(lat_f)*sin(delta/2)**2))
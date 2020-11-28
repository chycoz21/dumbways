def waktu(kalori):
    return kalori/10
#input data
kalori= int(input("jumlah kalori : "))
Waktu=waktu(kalori)
if kalori<=500:
    print(" jenis olahraga :badminton")
elif kalori<=750:
    print("jenis olahraga :lari")
elif kalori>=750:
    print("jenis olahraga :lari")
print("waktu olahraga",Waktu)
